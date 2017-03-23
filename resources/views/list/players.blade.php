<table class="table table-striped">
    <thead>
    <tr>
        <th>player</th>
        <th class="text-right">score</th>
        <th>notes</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(player, index) in session.players">
        <td>@{{ player.user.name }}</td>
        <td class="text-right">@{{ player.score }}</td>
        <td>@{{ player.notes }}</td>
        <td class="text-right">
            <button type="button" class="btn btn-sm btn-primary" @click.prevent="modalPlayerForEdit(index)">
                Edit
            </button>
            <button type="button" class="btn btn-sm btn-danger" @click.prevent="deletePlayer(index)">
                Delete
            </button>
        </td>
    </tr>
    </tbody>
</table>