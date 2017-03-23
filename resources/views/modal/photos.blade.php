{{--Photo modal--}}
<div class="modal fade" id="modal-photo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add photo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" action="/api/photos" @submit.prevent="addPhoto">

                    {{--Session--}}
                    <input type="hidden" name="game_session_id" v-model="photoForm.game_session_id"/>

                    {{--Photo file--}}
                    <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label">Photo:</label>
                        <div class="col-sm-9">
                            <input type="file" name="photo" class="form-control" id="photoInput"/>
                            <span v-if="formPhotoErrors['photo']" class="error text-danger">@{{ formPhotoErrors['photo'].toString() }}</span>
                        </div>
                    </div>

                    {{--Title--}}
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">Title:</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" v-model="photoForm.title"/>
                            <span v-if="formPhotoErrors['title']" class="error text-danger">@{{ formPhotoErrors['title'].toString() }}</span>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">Add photo</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>