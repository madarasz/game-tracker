<div class="card mb-3">
    <div class="card-block">
        <h5>
            ELO history<br/>
            @include('other.season-string')
        </h5>
        <div id="chart-elo-history" v-if="sessionList.length > 0"></div>
        <div class="small-text" v-if="sessionList.length == 0">
            no concluded sessions yet
        </div>
    </div>
</div>