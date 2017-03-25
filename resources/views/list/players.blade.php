<table class="table table-striped mt-3" v-if="session.players.length > 0">
    <thead>
    <tr>
        <th></th>
        <th>player</th>
        <th class="text-right">score</th>
        <th>notes</th>
        <th></th>
        <th v-if="session.concluded" class="info-text text-right">ELO</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(player, index) in session.players">
        <td style="width: 1%">
            <i class="fa fa-trophy" aria-hidden="true" v-if="parseInt(player.winner)"></i>
        </td>
        <td>
            @{{ player.user.name }}
        </td>
        <td class="text-right">@{{ player.score }}</td>
        <td>@{{ player.notes }}</td>
        <td class="text-right">
            @if ($user)
            <button type="button" class="btn btn-sm btn-info" @click.prevent="toggleWinner(index)" v-if="!session.concluded">
                <i class="fa fa-trophy" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-sm btn-primary" @click.prevent="modalPlayerForEdit(index)" v-if="!session.concluded">
                Edit
            </button>
            <button type="button" class="btn btn-sm btn-danger" @click.prevent="deletePlayer(index)" v-if="!session.concluded">
                Delete
            </button>
            @endif
        </td>
        <td class="text-right" v-if="session.concluded">
            <span class="text-info" v-if="session.concluded"><span v-if="player.eloDelta > 0">+</span>@{{ player.eloDelta }}</span>
        </td>
    </tr>
    </tbody>
</table>
<div class="small-text" v-if="session.players.length == 0">
    no players yet
</div>