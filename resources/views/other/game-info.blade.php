{{--Game info--}}
<div class="card">
    <img class="card-img-top img-fluid hidden-md-down" :src="game.thumbnail_url"/>
    <div class="card-block">
        <img class="hidden-lg-up img-thumb float-left mr-3" :src="game.thumbnail_url"/>
        <h4 class="card-title">@{{ game.title }}</h4>
        <p class="card-text">
        <p>@{{ game.description }}</p>
        <p>
            <em>
                number of sessions: @{{ game.sessionCount }}<br/>
                {{--number of players: 0--}}
            </em>
        </p>
        </p>
    </div>
</div>