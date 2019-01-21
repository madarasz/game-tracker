{{--Random faction modal--}}
<div class="modal fade" id="modal-random-faction" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Random faction picker</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body text-center" v-if="factionList.length" >
                <div>
                    <a :href="factionList[randomFactionId].factionFile" data-toggle="lightbox" data-gallery="gallery-random-faction">
                        <img :src="factionList[randomFactionId].iconFile" style="width: 60%"/>
                    </a>
                </div>
                <div>
                    <strong>@{{ factionList[randomFactionId].name }}</strong>
                </div>
                <button type="button" class="btn btn-primary mt-3" @click="randomFaction" class="random-mars-flex-contet">
                    Reroll
                </button>
            </div>
        </div>
    </div>
</div>