{{--Factions--}}
@include('modal.random-faction')
<div class="card">
    <div class="card-block">
        <a class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#modal-random-faction" @click="randomFaction">
            <i class="fa fa-cube text-white" title="pick random" aria-hidden="true"></i>
        </a>
        <h5 class="card-title">Factions</h5>
        {{--Factions list--}}
        <table class="small-text table vmiddle hover-row mt-3" v-if="factionList.length > 0" style="margin: 0 auto; max-width: 300px">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">faction</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(faction, index) in factionList">
                    <td>@{{ faction.playerNumber}}</td>
                    <td style="padding: 0.25rem" class="text-center">
                        <span v-if="faction.iconFile == null">@{{ faction.name }}</span>
                        <img v-if="faction.iconFile != null && faction.factionFile == null" :src="faction.iconFile" :alt="faction.name" style="max-width: 100%; max-height: 2rem" />
                        <a v-if="faction.iconFile != null && faction.factionFile != null" :href="faction.factionFile" data-toggle="lightbox" data-gallery="gallery-faction-list">
                            <img :src="faction.iconFile" :alt="faction.name" style="max-width: 100%; max-height: 2rem" />
                        </a>
                    </td>
                    <td class="text-right" style="padding: 0.25rem">
                        @if ($user)
                            <a @click.prevent="modalFactionForEdit(index)">
                                <i class="fa fa-pencil text-info" aria-hidden="true"></i>
                            </a>
                            <confirm-button v-if="faction.playerNumber == 0" button-icon="fa fa-trash text-danger" button-class="no-button"
                                @click="confirmCallback = function() { deleteFaction(faction.id) }; confirmText = 'Delete faction?'" />
                        @endif
                    </td>
                <tr>
            </tbody>
        </table>
        {{--No factions message--}}
        <div v-if="factionList.length == 0" class="small-text text-center mt-2">no factions added</div>
        {{--Create faction button--}}
        @if ($user)
            <div class="text-center mt-2">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                        data-target="#modal-faction" @click="modalFactionForCreate">
                Create
                </button>
            </div>
        @endif
    </div>
</div>