{{--Modal for new/edit game--}}
<div class="modal fade" id="modal-game" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">@{{ modalTitle }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" @submit.prevent="(editMode ? updateGame() : createGame())">

                    {{--Title--}}
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">Title:</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" v-model="game.title" />
                            <span v-if="formErrors['title']" class="error text-danger">@{{ formErrors['title'].toString() }}</span>
                        </div>
                    </div>

                    {{--Description--}}
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" class="form-control" v-model="game.description"></textarea>
                        <span v-if="formErrors['description']" class="error text-danger">@{{ formErrors['description'].toString() }}</span>
                    </div>

                    {{--Type--}}
                    <div class="form-group row">
                        <label for="game_type_id" class="col-sm-3 col-form-label">Type:</label>
                        <div class="col-sm-9">
                            <select name="game_type_id" class="form-control" v-model="game.game_type_id">
                                <option v-for="(type, index) in types" :value="index">@{{ type }}</option>
                            </select>
                            <span v-if="formErrors['game_type_id']" class="error text-danger">@{{ formErrors['game_type_id'].toString() }}</span>
                        </div>
                    </div>

                    {{--Thumbnail--}}
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <label for="thumbnail_url">Thumbnail URL:</label>
                            <input type="text" name="thumbnail_url" class="form-control" v-model="game.thumbnail_url" />
                            <span v-if="formErrors['thumbnail_url']" class="error text-danger">@{{ formErrors['thumbnail_url'].toString() }}</span>
                        </div>
                        <div class="col-sm-2">
                            <img :src="game.thumbnail_url" class="img-fluid"/>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">@{{ modalButton }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>