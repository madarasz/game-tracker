{{--Seasons--}}
<div class="card mt-3">
    <div class="card-block">
        <h5 class="card-title">Seasons</h5>
        {{--active--}}
        <div v-if="seasonList.length > 0">
            <strong>active: </strong>
            <span v-if="game.activeSeason == null" class="small-text pull-right">none</span>
            <span v-if="game.activeSeason !== null" class="pull-right">@{{ game.activeSeason.title }}</span>
            <div v-if="game.activeSeason !== null" class="small-text text-center">
                @{{ game.activeSeason.start_date }} - @{{ game.activeSeason.end_date }}
            </div>
            <div v-if="game.activeSeason !== null" class="small-text text-center">
                @{{ game.activeSeason.description }}
            </div>
        </div>
        {{--season list--}}
        <table class="small-text table table-striped vmiddle hover-row mt-3" v-if="seasonList.length > 0">
            <thead>
                <tr>
                    <th>name</th>
                    <th class="text-center">#sessions</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="season in seasonList" @click="displaySeason(season.id)" v-bind:class="{'row-highlight': season.id == requestedSeasonId}">
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" v-if="season.id == requestedSeasonId"></i>
                        <strong>@{{ season.title }}</strong>
                    </td>
                    <td class="text-center">
                        @{{ season.sessionCount }}
                    </td>
                    <td class="text-right">
                        @if ($user)
                            <confirm-button button-icon="fa fa-trash text-danger" button-class="no-button"
                                @click="confirmCallback = function() { deleteSeason(season.id) }; confirmText = 'Delete season?'" />
                        @endif
                    </td>
                </tr>
                <tr v-if="game.sessionsWithoutSeason > 0" @click="displaySeason(0)" v-bind:class="{'row-highlight': requestedSeasonId == 0}">
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" v-if="requestedSeasonId == 0"></i>
                        none
                    </td>
                    <td class="text-center">
                        @{{ game.sessionsWithoutSeason }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div v-if="seasonList.length == 0" class="small-text text-center mt-2">no seasons defined</div>
        {{--create season button--}}
        @if ($user)
            <div class="text-center mt-2">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                        data-target="#modal-season" @click="modalSeasonForCreate">
                Create
                </button>
            </div>
        @endif
    </div>
</div>