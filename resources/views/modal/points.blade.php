{{--Player modal--}}
<div class="modal fade" id="modal-points" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit points</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" @submit.prevent="pointMassEdit">

                    {{--Session--}}
                    <input type="hidden" name="game_session_id" v-model="pointForm.game_session_id" />

                    {{--Score--}}
                    <div class="form-group row" v-for="(player, index) in session.players">
                        <label for="place" class="col-sm-3 col-form-label">@{{ player.user.name }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" v-model="pointForm.score[index].v" />
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>