{{--Session modal--}}
<div class="modal fade" id="modal-session" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">@{{ modalSessionTitle }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" @submit.prevent="(sessionEditMode ? updateSession() : createSession())">

                    {{--Game--}}
                    <input type="hidden" name="game_id" v-model="sessionForm.game_id" />

                    {{--Date--}}
                    <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label">Date:</label>
                        <div class="col-sm-9">
                            <input type="text" name="date" class="form-control" v-model="sessionForm.date" />
                            <span v-if="formSessionErrors['date']" class="error text-danger">@{{ formSessionErrors['date'].toString() }}</span>
                        </div>
                    </div>

                    {{--Place--}}
                    <div class="form-group row">
                        <label for="place" class="col-sm-3 col-form-label">Place:</label>
                        <div class="col-sm-9">
                            <input type="text" name="place" class="form-control" v-model="sessionForm.place" />
                            <span v-if="formSessionErrors['place']" class="error text-danger">@{{ formSessionErrors['place'].toString() }}</span>
                        </div>
                    </div>

                    {{--Notes--}}
                    <div class="form-group">
                        <label for="notes">Notes:</label>
                        <textarea name="notes" class="form-control" v-model="sessionForm.notes"></textarea>
                        <span v-if="formSessionErrors['notes']" class="error text-danger">@{{ formSessionErrors['notes'].toString() }}</span>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">@{{ modalSessionButton }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>