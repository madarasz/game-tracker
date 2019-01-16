{{--Game info--}}
<div class="card" style="background-color: #fbfcd9">
    <a :href="'/games/' + game.id">
        <img class="card-img-top img-fluid hidden-md-down" :src="game.thumbnail_url"/>
    </a>
    <div class="card-block">
        <a :href="'/games/' + game.id">
            <img class="hidden-lg-up img-thumb float-left mr-3" :src="game.thumbnail_url"/>
        </a>
        <h4 class="card-title">@{{ game.title }}</h4>
        <p class="card-text">
        <p>@{{ game.description }}</p>
        <p class="small-text">
            
                number of sessions:
                <span class="text-info">@{{ getUserSessionCount(game.id) }}</span>
                / @{{ game.sessionCount }}
            
        </p>
        </p>
    </div>
</div>