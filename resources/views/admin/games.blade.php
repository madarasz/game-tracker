@extends('layout.general')

@section('content')
    <div class="card mt-3" id="manage-games">
        <div class="card-block">
            {{--Header--}}
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="card-title page-header">Games</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-game" @click="modalForCreate">
                        Create game
                    </button>
                </div>
            </div>

            {{--Item Listing--}}
            <table class="table table-bordered vmiddle hover-row mt-3">
                <thead>
                    <tr>
                        <th style="width: 200px"></th>
                        <th>title</th>
                        <th>description</th>
                        <th class="text-center">type</th>
                        <th class="text-center">#games</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" @click="navigateToGame(item.id)">
                        <td class="text-center">
                            <img :src="item.thumbnail_url" class="img-thumb"/>
                        </td>
                        <td>
                            @{{ item.title }}
                        </td>
                        <td>@{{ item.description }}</td>
                        <td class="text-center">@{{ types[item.game_type_id] }}</td>
                        <td class="text-center">-</td>
                        <td>
                            <button class="btn btn-primary" @click.prevent="modalForEdit($event, item)">Edit</button>
                            <button class="btn btn-danger" @click.prevent="deleteGame($event, item.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

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
    </div>
@stop

@section('script')
    <script type="text/javascript">
        var manageGames = new Vue({
            el: '#manage-games',

            data: {
                items: [],
                types: [],
                formErrors: {},
                game: {},
                modalTitle: '',
                modalButton: '',
                editMode: false
            },

            mounted: function() {
                this.getGameTypes();
                this.getGames();
            },

            methods: {
                // get list of game types
                getGameTypes: function() {
                    axios.get('/api/game-types').then(function (response) {
                        manageGames.types = response.data;
                    });
                },
                // get list of games
                getGames: function() {
                    axios.get('/api/games').then(function (response) {
                        manageGames.items = response.data;
                    });
                },
                // prepare modal for new game creation
                modalForCreate: function() {
                    this.game = {'game_type_id': 1};    // default values
                    this.formErrors = [];
                    this.modalTitle = 'Create Game';
                    this.modalButton = 'Create';
                    this.editMode = false;
                },
                // open modal for edit game
                modalForEdit: function(event, game) {
                    manageGames.game = game;
                    this.formErrors = [];add
                    this.modalTitle = 'Edit Game';
                    this.modalButton = 'Edit';
                    this.editMode = true;
                    $("#modal-game").modal('show');

                    // stop onclick propagation, no onclick on table row
                    gt.stopEventPropagation(event);
                },
                // create new game
                createGame: function() {
                    axios.post('/api/games', this.game)
                        .then(function(response) {
                                manageGames.getGames();
                                $("#modal-game").modal('hide');
                                toastr.info('Game created successfully.', '', {timeOut: 1000});
                            }, function(response) {
                                    // error handling
                                    manageGames.formErrors = response.response.data;
                            }
                    );
                },
                // delete game
                deleteGame: function(event, id) {
                    axios.delete('/api/games/' + id).then(function(response) {
                        manageGames.getGames();
                        toastr.info('Game deleted.', '', {timeOut: 1000});
                    });
                    // stop onclick propagation, no onclick on table row
                    gt.stopEventPropagation(event);
                },
                // update game
                updateGame: function() {
                    axios.put('/api/games/' + this.game.id, this.game)
                            .then(function(response) {
                                $("#modal-game").modal('hide');
                                toastr.info('Game updated successfully.', '', {timeOut: 1000});
                            }, function(response) {
                                // error handling
                                manageGames.formErrors = response.response.data;
                            }
                    );
                },
                // navigate to game
                navigateToGame: function(id) {
                    window.location.href = '/games/' + id;
                }
            }
        })
    </script>
@stop
