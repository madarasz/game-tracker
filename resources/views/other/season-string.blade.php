<span v-if="requestedSeasonId > 0" class="small-text">
    season:
    <em v-for="season in seasonList">
        <span v-if="season.id == requestedSeasonId">@{{ season.title }}</span>
    </em>
</span>
<span v-if="!requestedSeasonId" class="small-text">
    <em>without season</em>
</span>