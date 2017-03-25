{{--Leaderboard--}}
<div class="card mt-3">
    <div class="card-block">
        <h5 class="card-title">Leaderboard</h5>
        <ol>
            <li v-for="rank in ranking">
                @{{ rank.userName }} -
                <span class="text-info">@{{ rank.points }}</span>
            </li>
        </ol>
        <div class="small-text" v-if="ranking.length == 0">
            no concluded sessions yet
        </div>
    </div>
</div>