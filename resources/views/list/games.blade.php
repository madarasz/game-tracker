{{--Game Listing--}}
<table class="table table-striped vmiddle hover-row mt-3">
    <thead>
    <tr>
        <th style="width: 200px"></th>
        <th>title</th>
        <th>description</th>
        <th class="text-center">type</th>
        <th class="text-center">#sessions</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in items" @click="navigateToGame(item.id)">
    <td class="text-center">
        <img :src="item.thumbnail_url" class="img-thumb"/>
    </td>
    <td>
        @{{ item.title }}
    </td>
    <td>@{{ item.description }}</td>
    <td class="text-center">@{{ types[item.game_type_id] }}</td>
    <td class="text-center">@{{ item.sessionCount }}</td>
    <td class="text-right">
        <button class="btn btn-primary btn-sm" @click.stop="modalForEdit($event, item)">Edit</button>
        <button class="btn btn-danger btn-sm" @click.stop="deleteGame($event, item.id)">Delete</button>
    </td>
    </tr>
    </tbody>
</table>