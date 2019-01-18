<table class="table table-striped mt-3" v-if="session.players.length > 0">
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th>player</th>
        <th class="text-right">score</th>
        <th>notes</th>
        <th></th>
        <th v-if="parseInt(session.concluded)" class="info-text text-right">ELO</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(player, index) in session.players">
        <td style="padding: 0.5rem" class="text-right">
            <div v-if="player.faction_id != null">
                {{--Text--}}
                <span v-if="getFactionById(player.faction_id).iconFile == null" class="small-text">@{{ getFactionById(player.faction_id).name }}</span>
                {{--Photo and logo--}}
                <a v-if="getFactionById(player.faction_id).iconFile != null && getFactionById(player.faction_id).factionFile != null" :href="getFactionById(player.faction_id).factionFile" data-toggle="lightbox" data-gallery="gallery-factions">
                    <img :src="getFactionById(player.faction_id).iconFile" :alt="getFactionById(player.faction_id).name" style="max-width: 100%; max-height: 2rem" />
                </a>
                {{--Logo only--}}
                <img v-if="getFactionById(player.faction_id).iconFile != null && getFactionById(player.faction_id).factionFile == null" :src="getFactionById(player.faction_id).iconFile" :alt="getFactionById(player.faction_id).name" style="max-width: 100%; max-height: 2rem" />
            </div>
        </td>
        <td style="width: 1%">
            <i class="fa fa-trophy" aria-hidden="true" v-if="parseInt(player.winner)"></i>
        </td>
        <td>
            <a :href="'/user-details/'+player.user.id" style="color: black">
                @{{ player.user.name }}
            </a>
        </td>
        <td class="text-right">@{{ player.score }}</td>
        <td>@{{ player.notes }}</td>
        <td class="text-right">
            @if ($user)
            {{--<button type="button" class="btn btn-sm btn-info" @click.prevent="toggleWinner(index)" v-if="!parseInt(session.concluded)">--}}
                {{--<i class="fa fa-trophy" aria-hidden="true"></i>--}}
            {{--</button>--}}
            <button type="button" class="btn btn-sm btn-primary" @click.prevent="modalPlayerForEdit(index)" v-if="!parseInt(session.concluded)">
                Edit
            </button>
            <confirm-button button-text="Delete" button-class="btn btn-sm btn-danger" v-if="!parseInt(session.concluded)"
                @click="confirmCallback = function() { deletePlayer(index) }; confirmText = 'Delete player?'" />
            @endif
        </td>
        <td class="text-right" v-if="parseInt(session.concluded)">
            <span v-if="player.eloDelta > 0">+</span>@{{ player.eloDelta }}
        </td>
    </tr>
    </tbody>
</table>
<div class="small-text" v-if="session.players.length == 0">
    no players yet
</div>