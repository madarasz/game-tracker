<?php

namespace App\Http\Controllers;

use App\EloDelta;
use App\EloPoint;
use App\Game;
use App\GameSession;
use App\Player;
use App\GameFaction;
use Illuminate\Http\Request;

class PointController extends Controller
{

    // ELO "K" factor
    protected $k = 32;

    public function concludeSession($id, $force = false) {
        $session = GameSession::findOrFail($id);

        // fail if already concluded
        if ($session->concluded && !$force) {
            return response()->json("Session already concluded");
        }

        $players = $session->players;

        foreach ($players as $player) {
            // create default score if player hasn't played game yet
            if (!$player->elo_score($session->game_id, $session->season_id)) {
                EloPoint::create([
                    'game_id' => $session->game_id,
                    'user_id' => $player->user_id,
                    'points' => 1500,
                    'season_id' => $session->season_id
                ]);
            }
            // create delta object, or set it to 0
            if (!$player->elo_delta()) {
                EloDelta::create([
                    'user_id' => $player->user_id,
                    'game_session_id' => $session->id,
                    'delta' => 0
                ]);
            } else {
                $player->elo_delta()->update(['delta' => 0]);
            }
        }

        // iterate over player pairs
        foreach ($players as $i => $player) {

            for ($u = $i + 1; $u < count($players); $u++) {

                // decide who won
                $s1 = $this->whoWon($players[$i], $players[$u]);

                // calculate elo delta
                $delta = $this->calculateEloDelta(
                    $players[$i]->elo_score($session->game_id, $session->season_id)->points,
                    $players[$u]->elo_score($session->game_id, $session->season_id)->points,
                    $s1
                );

                $players[$i]->elo_delta()->update(['delta' => $players[$i]->elo_delta()->delta + $delta[0]]);
                $players[$u]->elo_delta()->update(['delta' => $players[$u]->elo_delta()->delta + $delta[1]]);
            }

        }

        // update elo rankings per player
        foreach ($players as $player) {
            $player->elo_score($session->game_id, $session->season_id)
                ->update(['points' => $player->elo_score($session->game_id, $session->season_id)->points + $player->elo_delta()->delta]);
        }

        $session->update(['concluded' => true]);

        // update faction elo
        $this->recalculateFactionElo($session->game_id);
        
        return response()->json($session);
    }

    public function whoWon($player1, $player2) {
        if ($player1->score > $player2->score) {
            return 1;
        } else {
            if ($player1->score == $player2->score) { // draw
                if ($player1->winner == $player2->winner) {
                    return 0.5;
                } elseif ($player1->winner) {   // winner flag decides draw
                    return 1;
                } 
            }
        }
        return 0;
    }

    public function calculateEloDelta($elo1, $elo2, $win1) {
        $r1 = pow(10, $elo1 / 400);
        $r2 = pow(10, $elo2 / 400);
        $e1 = $r1 / ($r1 + $r2);
        $e2 = $r2 / ($r1 + $r2);
        $win2 = 1 - $win1;
        $delta1 = (int) round($this->k * ($win1 - $e1));
        $delta2 = (int) round($this->k * ($win2 - $e2));
        return array($delta1, $delta2);
    }

    public function recalculateGame($gameid, $seasonid) {
        $game = Game::findOrFail($gameid);
        if ($seasonid == 0) {
            $seasonid = null;
        }

        // reset points
        EloPoint::where('game_id', $gameid)->where('season_id', $seasonid)->delete();

        foreach($game->sessions as $session) {
            if (intval($session->season_id) == intval($seasonid) && $session->concluded) {
                $this->concludeSession($session->id, true);
            }
        }

        $this->recalculateFactionElo($gameid);

        return $this->getGameRanking($gameid, $seasonid);
    }

    public function getGameRanking($id, $seasonid = null) {
        if ($seasonid == 0) {
            $seasonid = null;
        }

        $game = Game::find($id);
        return response()->json($game->elo_ranking($seasonid));
    }

    public function historyForGame($gameid, $seasonid) {
        if ($seasonid == 0) {
            $seasonid = null;
        }

        $sessionIds = GameSession::where('season_id', $seasonid)->where('game_id', $gameid)
            ->orderBy('date', 'asc')->orderBy('id','asc')->pluck('id');
        $sessions = GameSession::where('season_id', $seasonid)->where('game_id', $gameid)
            ->orderBy('date', 'asc')->orderBy('id','asc')->get();


        $userIds = Player::whereIn('game_session_id', $sessionIds)->groupBy('user_id')->pluck('user_id');

        $history = ['history' => [], 'user_list' => $userIds];

        $scores = [];
        foreach($userIds as $userId) {
            $scores[$userId] = 1500;
        }

        foreach($sessions as $session) {

            $playerScores = [];
            foreach($userIds as $userId) {
                $delta = EloDelta::where('user_id', $userId)->where('game_session_id', $session->id)->first();
                $deltaPoint = $delta ? $delta->delta : 0;
                $scores[$userId] += $deltaPoint;

                $playerScores[$userId] = $scores[$userId];
            }

            array_push($history['history'], [
                'session_id' => $session->id,
                'date' => $session->date,
                'player_scores' => $playerScores
            ]);
        }

        return response()->json($history);
    }

    public function recalculateFactionElo($gameid) {
        $game = Game::findOrFail($gameid);
        $factions = GameFaction::where('game_id', $gameid)->get()->makeHidden(['game_id', 'iconFile', 'factionFile', 'photo', 'bigPhoto']);
        $faction_elo = [];
        foreach($factions as $faction) {
            $faction_elo[$faction->id] = 1500;
        }
        foreach($game->sessions as $x=>$session) {
            foreach($factions as $faction) {
                $factiondelta[$faction->id] = 0;
            }
            $players = $session->players;
            foreach ($players as $i => $player) {
                for ($u = $i + 1; $u < count($players); $u++) {
                    if ((!is_null($players[$i]->faction)) && (!is_null($players[$u]->faction))) {
                        // decide who won
                        $s1 = $this->whoWon($players[$i], $players[$u]);
                        $faction1_id = $players[$i]->faction_id;
                        $faction2_id = $players[$u]->faction_id;
                        // calculate elo delta
                        $delta = $this->calculateEloDelta(
                            $faction_elo[$faction1_id],
                            $faction_elo[$faction2_id],
                            $s1
                        );
                        $factiondelta[$faction1_id] += $delta[0];
                        $factiondelta[$faction2_id] += $delta[1];
                    }
                }
            }
            foreach($factions as $faction) {
                $faction_elo[$faction->id] += $factiondelta[$faction->id];
            }
        }
        foreach($factions as $faction) {
            $faction->update(['elo' => $faction_elo[$faction->id]]);
        }
        $sorted = $factions->sortByDesc('elo')->makeHidden('id');
        return response()->json($sorted->values()->all());
    }

    public function winrateFaction($factionid, $http = true) {
        $faction = GameFaction::findOrFail($factionid);
        $gameid = $faction->id;
        $sessionids = Player::where('faction_id', $factionid)->pluck('game_session_id')->toArray();
        $sessions = GameSession::whereIn('id', $sessionids)->get();
        $players = Player::whereIn('game_session_id', $sessionids)->get();
        $result = [
            'id' => $faction->id,
            'factionName' => $faction->name,
            'elo' => $faction->elo,
            'sessionCount' => count($sessionids),
            'winratePerPlayerNumber' => [],
            'winrate' => 0
        ];
        if (count($sessionids) == 0) return $result;
        
        // iterate sessions with faction
        $wincount = 0;
        $session_counter = array_fill(2, 10, 0);
        $win_counter = array_fill(2, 10, 0);
        foreach($sessions as $session) {
            $session_players = $players->where('game_session_id', $session->id);
            $player_num = count($session_players);
            $session_counter[$player_num]++;
            if ($session_players->where('winner', 1)->where('faction_id', $factionid)->count() > 0) {
                $wincount++;
                $win_counter[$player_num]++;
            }
        }
        // collect winrates per player number
        foreach($session_counter as $player_num=>$counter) {
            if ($counter > 0) {
                $result['winratePerPlayerNumber'][$player_num]['sessionCount'] = $counter;
                $result['winratePerPlayerNumber'][$player_num]['winrate'] = $win_counter[$player_num] / $counter;
            }
        }
        $result['winrate'] = $wincount / count($sessionids);
        if ($http) {
            return response()->json($result);
        } else {
            return $result;
        }
    }

    public function winrateGame($gameid) {
        $factions = GameFaction::where('game_id', $gameid)->get();
        $result = [];
        foreach($factions as $faction) {
            array_push($result, $this->winrateFaction($faction->id, false));
        }
        return response()->json($result);
    }
}
