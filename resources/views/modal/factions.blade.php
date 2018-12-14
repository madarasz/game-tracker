{{--Factions modal--}}
<div class="modal fade" id="modal-faction" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">@{{ modalFactionTitle }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" @submit.prevent="(factionEditMode ? updateFaction() : createFaction())">

                    {{--Game--}}
                    <input type="hidden" name="game_id" v-model="factionForm.game_id" />

                    {{--Name--}}
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label">Faction name:</label>
                        <div class="col-sm-8 ">
                            <input type="text" name="name" class="form-control" v-model="factionForm.name" />
                        </div>
                    </div>

                    {{--Picture--}}
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Logo:</label>
                        {{--without logo--}}
                        <div class="col-sm-6" v-if="factionForm.iconFile == null">
                            <input type="file" name="photo" class="form-control" id="logoInput"/>
                        </div>
                        <div class="col-sm-3" v-if="factionForm.iconFile == null">
                            <a class="btn btn-sm btn-primary text-white" @click="addPhoto(false)">Upload</a>
                        </div>
                        {{--with logo--}}
                        <div class="col-sm-7" v-if="factionForm.iconFile != null">
                            <img :src="factionForm.iconFile" style="max-width: 100%"/>
                        </div>
                        <div class="col-sm-2" v-if="factionForm.iconFile != null">
                            <a class="btn btn-sm btn-danger text-white" @click.prevent="removeFactionPhoto">
                                <i class="fa fa-trash" aria-hidden="true"></i> 
                            </a>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">@{{ modalFactionButton }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>