{{--Session table--}}
<table class="table table-striped vmiddle hover-row mt-3">
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