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
        <div class="row pb-3" v-for="game in games">
            {{--Game info--}}
            <div class="col-lg-3">
                @include('other.user-game-info')
            </div>
            {{--Season list--}}
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-block">
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
            <div class="col-lg-4" v-if="game.selectedSeasonId > -1">
                <div class="card">
                    <div class="card-block" style="font-size: 90%">
                        <div class="text-center pb-1">
                            <strong>@{{ game.title }} season:</strong>
                            @{{ getSeason(game.id, game.selectedSeasonId).title }}
                        </div>
                        <table class="table table-striped table-sm " style="max-width: 200px; font-size: 90%; margin: 0 auto">
                            <tr v-for="(point, index) in getSeason(game.id, game.selectedSeasonId).points">
                                <td>@{{ (index+1) }}.</td>
                                <td>
                                    <span :style="point.user_id == userId ? 'font-weight: bolder' : ''">
                                        @{{ point.userName }}
                                    </span>
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
