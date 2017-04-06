{{--Game Listing--}}
<table class="table table-striped vmiddle hover-row mt-3" v-if="items.length > 0">
    <thead>
    <tr>
        <th style="width: 200px"></th>
        <th>title</th>
        <th>leader</th>
        <th class="text-center">type</th>
        <th class="text-center">#sessions</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in items">
        <td class="text-center" @click="navigateToGame(item.id)">
            <img :src="item.thumbnail_url" class="img-thumb"/>
        </td>
        <td @click="navigateToGame(item.id)">
            @{{ item.title }}
        </td>
        <td @click="navigateToGame(item.id)">
            <span v-if="item.leader">
                @{{ item.leader.userName }}&nbsp;-&nbsp;<span class="text-info">@{{ item.leader.points }}</span>
            </span>
            <span v-if="!item.leader" class="small-text">
                no session yet
            </span>
        </td>
        <td class="text-center" @click="navigateToGame(item.id)">@{{ types[item.game_type_id] }}</td>
        <td class="text-center" @click="navigateToGame(item.id)">@{{ item.sessionCount }}</td>
        <td class="text-right">
            @if ($user)
            <button class="btn btn-primary btn-sm" @click.stop="modalForEdit(item)">Edit</button>
            <confirm-button button-text="Delete" button-class="btn btn-sm btn-danger"
                            @click="confirmCallback = function() { deleteGame(item.id) }; confirmText = 'Delete game?'" />
            @endif
        </td>
    </tr>
    </tbody>
</table>
<div class="small-text" v-if="items.length == 0">
    no games yet
</div>