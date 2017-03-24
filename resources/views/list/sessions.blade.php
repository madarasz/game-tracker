{{--Session table--}}
<table class="table table-striped vmiddle hover-row mt-3" v-if="sessionList.length > 0">
    <thead>
        <tr>
            <th>date</th>
            <th>place</th>
            <th>players (score)</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="gsession in sessionList" @click="displaySession(gsession.id)">
            <td>@{{ gsession.date }}</td>
            <td>@{{ gsession.place }}</td>
            <td>
                {{--Player list--}}
                <span v-for="(player, index) in gsession.players" :class="(parseInt(player.winner) ? 'font-weight-bold' : '')">
                    @{{ player.user.name }} (@{{ player.score }})<span v-if="index < gsession.players.length -1">, </span>
                </span>
                {{--Photo counter--}}
                <span v-if="gsession.photoCount > 0" class="pull-right">
                    <span v-if="gsession.photoCount > 1">@{{ gsession.photoCount }}</span>
                    <i class="fa fa-camera-retro" aria-hidden="true"></i>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<div class="small-text" v-if="sessionList.length == 0">
    no sessions yet
</div>