{{--Leaderboard--}}
<div class="card mt-3">
    <div class="card-block">
        <h5 class="card-title">
            ELO Leaderboard
            @include('other.season-string')
        </h5>
        <table class="table table-striped table-sm " style="max-width: 200px; font-size: 90%">
            <tr v-for="(rank, index) in ranking">
                <td>@{{ (index+1) }}.</td>
                <td>
                    @{{ rank.userName }}
                    <span class="text-info">(@{{ countPlayerSession(rank.user_id) }})</span>
                </td>
                <td class="text-right text-primary">
                    @{{ rank.points }}
                </td>
            </tr>
        </table>
        <div class="small-text" v-if="ranking.length == 0">
            no concluded sessions yet
        </div>
        @if ($user)
        <div class="text-center mt-3" v-if="ranking.length > 0">
            <confirm-button button-text="Recalculate" button-class="btn btn-sm btn-danger"
                            @click="confirmCallback = function() { recalculateELO() }; confirmText = 'Recalculate rankings?'" />
        </div>
        @endif
    </div>
</div>