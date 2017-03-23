{{--Player modal--}}
<div class="modal fade" id="modal-player" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">@{{ modalPlayerTitle }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" @submit.prevent="(playerEditMode ? updatePlayer() : createPlayer())">

                    {{--Session--}}
                    <input type="hidden" name="game_session_id" v-model="playerForm.game_session_id" />

                    {{--User--}}
                    <div class="form-group row">
                        <label for="user_id" class="col-sm-3 col-form-label">Player:</label>
                        <div class="col-sm-9">
                            <select v-model="playerForm.user_id" class="form-control" name="user_id">
                                <option v-for="user in users" :value="user.id">@{{ user.name }}</option>
                            </select>
                            <span v-if="formPlayerErrors['user_id']" class="error text-danger">@{{ formPlayerErrors['user_id'].toString() }}</span>
                        </div>
                    </div>

                    {{--Score--}}
                    <div class="form-group row">
                        <label for="place" class="col-sm-3 col-form-label">Score:</label>
                        <div class="col-sm-9">
                            <input type="text" name="score" class="form-control" v-model="playerForm.score" />
                            <span v-if="formPlayerErrors['score']" class="error text-danger">@{{ formPlayerErrors['score'].toString() }}</span>
                        </div>
                    </div>

                    {{--Notes--}}
                    <div class="form-group">
                        <label for="notes">Notes:</label>
                        <textarea name="notes" class="form-control" v-model="playerForm.notes"></textarea>
                        <span v-if="formPlayerErrors['notes']" class="error text-danger">@{{ formPlayerErrors['notes'].toString() }}</span>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">@{{ modalPlayerButton }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>