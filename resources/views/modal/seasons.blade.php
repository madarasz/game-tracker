{{--Seasons modal--}}
<div class="modal fade" id="modal-season" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">@{{ modalSeasonTitle }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" @submit.prevent="createSeason">

                    {{--Game--}}
                    <input type="hidden" name="game_id" v-model="seasonForm.game_id" />

                    {{--Title--}}
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">Title:</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" v-model="seasonForm.title" />
                            <span v-if="formSeasonErrors['title']" class="error text-danger">@{{ formSeasonErrors['title'].toString() }}</span>
                        </div>
                    </div>

                    {{--Start date--}}
                    <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label">Start date:</label>
                        <div class="col-sm-9">
                            <input type="text" name="start_date" class="form-control" v-model="seasonForm.start_date" />
                            <span v-if="formSeasonErrors['start_date']" class="error text-danger">@{{ formSeasonErrors['start_date'].toString() }}</span>
                        </div>
                    </div>

                    {{--End date--}}
                    <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label">End date:</label>
                        <div class="col-sm-9">
                            <input type="text" name="end_date" class="form-control" v-model="seasonForm.end_date" />
                            <span v-if="formSeasonErrors['end_date']" class="error text-danger">@{{ formSeasonErrors['end_date'].toString() }}</span>
                        </div>
                    </div>

                    {{--Description--}}
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" class="form-control" v-model="seasonForm.description"></textarea>
                        <span v-if="formSeasonErrors['description']" class="error text-danger">@{{ formSeasonErrors['description'].toString() }}</span>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">Create season</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>