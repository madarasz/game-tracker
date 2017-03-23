{{--Session table--}}
<table class="table table-striped vmiddle hover-row mt-3" v-if="sessionList.length > 0">
    <thead>
    <tr>
        <th>date</th>
        <th>place</th>
        <th>players</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="gsession in sessionList" @click="displaySession(gsession.id)">
    <td>@{{ gsession.date }}</td>
    <td>@{{ gsession.place }}</td>
    <td></td>
    </tr>
    </tbody>
</table>
<div class="small-text" v-if="sessionList.length == 0">
    no sessions yet
</div>