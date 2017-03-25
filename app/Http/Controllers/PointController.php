<?php

namespace App\Http\Controllers;

use App\EloDelta;
use App\EloPoint;
use App\Game;
use App\GameSession;
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
            if (!$player->elo_score($session->game_id)) {
                EloPoint::create([
                    'game_id' => $session->game_id,
                    'user_id' => $player->user_id,
                    'points' => 1500
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

                // calculate elo delta
                $r1 = pow(10, $players[$i]->elo_score($session->game_id)->points / 400);
                $r2 = pow(10, $players[$u]->elo_score($session->game_id)->points / 400);
                $e1 = $r1 / ($r1 + $r2);
                $e2 = $r2 / ($r1 + $r2);
                // TODO: draws
                $s1 = $players[$i]->score > $players[$u]->score ? 1 : 0;
                $s2 = 1 - $s1;
                $delta1 = (int) round($this->k * ($s1 - $e1));
                $delta2 = (int) round($this->k * ($s2 - $e2));

                $players[$i]->elo_delta()->update(['delta' => $players[$i]->elo_delta()->delta + $delta1]);
                $players[$u]->elo_delta()->update(['delta' => $players[$u]->elo_delta()->delta + $delta2]);
            }

        }

        // update elo rankings per player
        foreach ($players as $player) {
            $player->elo_score($session->game_id)
                ->update(['points' => $player->elo_score($session->game_id)->points + $player->elo_delta()->delta]);
        }

        $session->update(['concluded' => true]);
        return response()->json($session);
    }

    public function recalculateGame($gameid) {
        $game = Game::findOrFail($gameid);

        // reset points
        EloPoint::where('game_id', $gameid)->delete();

        foreach($game->sessions() as $session) {
            if ($session->concluded) {
                $this->concludeSession($session->id, true);
            }
        }

        return $this->getGameRanking($gameid);
    }

    public function getGameRanking($id) {
        $game = Game::find($id);
        return response()->json($game->elo_ranking);
    }
}
