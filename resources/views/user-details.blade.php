@extends('layout.general')

@section('content')
    <div id="user-details">
        <div class="row pb-3">
            <div class="col-sm-12">
                <div class="card text-white" style="background-color: #343a40;">
                    <div class="card-block text-center">
                        <h1>
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            @{{ user.name }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pb-3" v-for="(game, gameIndex) in games" v-if="dataLoaded">
            {{--Game info--}}
            <div class="col-lg-3">
                @include('other.user-game-info')
            </div>
            <div class="col-lg-5">
                {{--Faction List--}}
                <div class="card mb-2" v-if="game.factionStats">
                    <div class="card-block text-center p-2" style="font-size: 90%">
                        <strong>top faction stats</strong>
                        <table class="table vmiddle borderless table-sm" style="max-width: 200px; margin: 0 auto">
                            <tr v-for="(faction, ind) in game.factionStats" v-if="ind < 5 || game.displayAllFactions">
                                <td class="text-right">@{{ faction.total }}</td>
                                <td>
                                    <a :href="'/img/photos/'+faction.corp" data-toggle="lightbox" data-gallery="gallery-factions">
                                        <img :src="'/img/photos/'+faction.icon" :alt="faction.name" style="max-width: 100%; max-height: 1.5rem" />
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{--Season list--}}
                <div class="card">
                    <div class="card-block p-2">
                        <table class="table table-striped vmiddle borderless hover-row" style="font-size: 90%">
                            <thead>
                                <th>season</th>
                                <th class="text-center">sessions</th>
                                <th class="text-center">rank</th>
                            </thead>
                            <tbody>
                                <tr v-for="season in game.seasons" @click="selectSeason(game.id, season.id)" :class="game.selectedSeasonId == season.id ? 'row-highlight' : ''">
                                    <td>
                                        <span v-if="season.id">
                                            @{{ season.title }}
                                            <span v-if="game.activeSeason && game.activeSeason.id == season.id" class="small-text">
                                                (active)
                                        </span>
                                        </span>
                                        <span v-if="!season.id" class="small-text">without season</span>
                                    </td>
                                    <td v-if="getPointsInSeason(game.id, season.id)" class="text-center">
                                        <span class="text-info">
                                            @{{ getPointsInSeason(game.id, season.id).sessionCount }}
                                        </span>
                                        / @{{ season.sessionCount }}
                                    </td>
                                    <td v-if="getPointsInSeason(game.id, season.id)" class="text-center">
                                        <i v-if="getUserRank(game.id, season.id) == 1" aria-hidden="true" class="fa fa-trophy"></i>
                                        <strong>
                                            @{{ getUserRank(game.id, season.id) }} / @{{ getSeason(game.id, season.id).points.length }}
                                        </strong>
                                    </td>
                                    <td v-if="!getPointsInSeason(game.id, season.id)" colspan="2" class="text-center small-text">
                                        did not participate
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{--Season details--}}
            <div class="col-lg-4" v-if="game.selectedSeasonId > -1">
                <div class="card" style="background-color: #d9fce1">
                    <div class="card-block p-2" style="font-size: 90%">
                        <div class="text-center pb-1">
                            <strong>@{{ game.title }} season:</strong>
                            @{{ getSeason(game.id, game.selectedSeasonId).title }}
                            <span class="text-info">(@{{ getSeason(game.id, game.selectedSeasonId).sessionCount }})</span>
                            <div v-if="getSeason(game.id, game.selectedSeasonId).points.length == 0" class="small-text">
                                no sessions yet
                            </div>
                        </div>
                        <table class="table table-striped table-sm " style="max-width: 200px; font-size: 90%; margin: 0 auto">
                            <tr v-for="(point, index) in getSeason(game.id, game.selectedSeasonId).points">
                                <td>@{{ (index+1) }}.</td>
                                <td>
                                    <span v-if="point.user_id == userId " style="font-weight: bolder">
                                        @{{ point.userName }}
                                    </span>
                                    <a v-if="point.user_id != userId" :href="'/user-details/' + point.user_id" style="color: black">
                                        @{{ point.userName }}
                                    </a>
                                    <span class="text-info">(@{{ point.sessionCount }})</span>
                                </td>
                                <td class="text-right text-primary">
                                    @{{ point.points }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    @include('script.user-details')
@stop
