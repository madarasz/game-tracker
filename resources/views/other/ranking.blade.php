{{--Leaderboard--}}
<div class="card mt-3">
    <div class="card-block">
        <h5 class="card-title">ELO Leaderboard</h5>
        <ol>
            <li v-for="rank in ranking">
                @{{ rank.userName }} -
                <span class="text-info">@{{ rank.points }}</span>
            </li>
        </ol>
        <div class="small-text" v-if="ranking.length == 0">
            no concluded sessions yet
        </div>
        @if ($user)
        <div class="text-center mt-3" v-if="ranking.length > 0">
            <button type="button" class="btn btn-sm btn-danger" @click="recalculateELO">
                Recalculate
            </button>
        </div>
        @endif
    </div>
</div>