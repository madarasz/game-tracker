@extends('layout.general')

@section('content')
    <div class="row mt-3" id="game-viewer">
        <div class="col-sm-12 col-lg-9 push-lg-3">
            {{--Session detais--}}
            <div class="card mb-3" v-if="session.date">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="card-title page-header">Session details</h4>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" class="btn btn-sm btn-primary" @click.prevent="modalSessionForEdit">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" @click.prevent="deleteSession">
                                Delete
                            </button>
                            <button type="button" class="close ml-1" aria-label="Close" @click.prevent="session = {}">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>date:</strong> @{{ session.date }}<br/>
                            <strong>place:</strong> @{{ session.place }}<br/>
                            <strong>notes:</strong> @{{ session.notes }}<br/>
                        </div>
                    </div>
                    {{--Players--}}
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <h5>Players</h5>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#modal-player" @click="modalPlayerForCreate">
                                Add player
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>player</th>
                                        <th class="text-right">score</th>
                                        <th>notes</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(player, index) in session.players">
                                        <td>@{{ player.user.name }}</td>
                                        <td class="text-right">@{{ player.score }}</td>
                                        <td>@{{ player.notes }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" @click.prevent="modalPlayerForEdit(index)">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" @click.prevent="deletePlayer(index)">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--Photos--}}
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <h5>Photos</h5>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#modal-photo" @click="photoForm.game_session_id = session.id">
                                Add photo
                            </button>
                        </div>
                    </div>
                    {{--Gallery--}}
                    <div class="row">
                        <div class="gallery-item" v-for="photo in session.photos">
                            <div style="position: relative;">
                                {{--image--}}
                                <a :href="photo.url" data-toggle="lightbox"
                                   data-gallery="gallery" :data-footer="photo.title">
                                    <img :src="photo.thumbnail_url" />
                                </a>
                                {{--delete button--}}
                                <button type="button" class="btn btn-sm btn-danger abs-top-left fade-in" @click.prevent="deletePhoto(photo.id)">
                                    X
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--Sessions header--}}
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="card-title page-header">Game sessions</h4>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#modal-session" @click="modalSessionForCreate">
                                Create session
                            </button>
                        </div>
                    </div>
                    <table class="table table-bordered vmiddle hover-row mt-3">
                        <thead>
                            <tr>
                                <th>date</th>
                                <th>place</th>
                                <th>players</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="gsession in sessionList" @click="displaySession(gsession.id)">
                                <td>@{{ gsession.date }}</td>
                                <td>@{{ gsession.place }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-sm-12 col-lg-3 pull-lg-9">
            {{--Game info--}}
            <div class="card">
                <img class="card-img-top img-fluid hidden-md-down" :src="game.thumbnail_url"/>
                <div class="card-block">
                    <img class="hidden-lg-up img-thumb float-left mr-3" :src="game.thumbnail_url"/>
                    <h4 class="card-title">@{{ game.title }}</h4>
                    <p class="card-text">
                        <p>@{{ game.description }}</p>
                        <p>
                            <em>
                            number of games: 0<br/>
                            number of players: 0
                            </em>
                        </p>
                    </p>
                </div>
            </div>
            {{--Leaderboard--}}
            <div class="card mt-3">
                <div class="card-block">
                    <h5 class="card-title">Leaderboard</h5>
                    <p class="card-text">
                        <p>
                            top score: <em style="font-size: 80%">not yet developed</em>
                        </p>
                        <p>
                            <em style="font-size: 80%">not yet developed</em>
                        </p>
                    </p>
                </div>
            </div>
        </div>

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
@stop

@section('script')
    <script type="text/javascript">
        var viewGame = new Vue({
            el: '#game-viewer',

            data: {
                id: '{{ $id }}',
                game: {},
                users: [],
                session: {},
                sessionForm: {},
                sessionList: [],
                formSessionErrors: [],
                modalSessionTitle: '',
                modalSessionButton: '',
                sessionEditMode: false,
                playerForm: {},
                formPlayerErrors: [],
                modalPlayerTitle: '',
                modalPlayerButton: '',
                playerEditMode: false,
                photoForm: { title: ''},
                formPhotoErrors: []
            },

            mounted: function() {
                this.loadGame();
                this.listSessionsForGame();
                this.loadUsers();
            },

            methods: {
                // prepare modal for create session
                modalSessionForCreate: function() {
                    this.sessionForm = { game_id: '{{ $id }}', date: ''};
                    this.formSessionErrors = [];
                    this.modalSessionTitle = 'Create session';
                    this.modalSessionButton = 'Create';
                    this.sessionEditMode = false;
                },
                // prepare modal for edit session
                modalSessionForEdit: function() {
                    this.sessionForm = this.session;
                    this.formSessionErrors = [];
                    this.modalSessionTitle = 'Edit session';
                    this.modalSessionButton = 'Save';
                    this.sessionEditMode = true;
                    $("#modal-session").modal('show');
                },
                // prepare modal for create player
                modalPlayerForCreate: function() {
                    this.playerForm = { game_session_id : this.session.id};
                    this.formPlayerErrors = [];
                    this.modalPlayerTitle = 'Add player';
                    this.modalPlayerButton = 'Add';
                    this.playerEditMode = false;
                },
                // prepare modal for edit player
                modalPlayerForEdit: function(index) {
                    this.playerForm = this.session.players[index];
                    this.playerForm.game_session_id = this.session.id;
                    this.playerForm.user_id = this.session.players[index].user.id;
                    this.formPlayerErrors = [];
                    this.modalPlayerTitle = 'Edit player';
                    this.modalPlayerButton = 'Edit';
                    this.playerEditMode = true;
                    $("#modal-player").modal('show');
                },

                // loads basic info about the game
                loadGame: function() {
                    axios.get('/api/games/' + this.id).then(function (response) {
                        viewGame.game = response.data;
                    });
                },
                // list sessions for game
                listSessionsForGame: function() {
                    axios.get('/api/game-sessions/game/' + this.id).then(function (response) {
                        viewGame.sessionList = response.data;
                    });
                },
                // loads users
                loadUsers: function() {
                    axios.get('/api/users').then(function (response) {
                        viewGame.users = response.data;
                    });
                },
                // display session
                displaySession: function(id) {
                    axios.get('/api/game-sessions/' + id).then(function (response) {
                        viewGame.session = response.data;
                    });
                },
                // creates session
                createSession: function() {
                    axios.post('/api/game-sessions', this.sessionForm)
                            .then(function(response) {
                                viewGame.displaySession(response.data.id);
                                viewGame.listSessionsForGame();
                                $("#modal-session").modal('hide');
                                toastr.info('Session created successfully.', '', {timeOut: 1000});
                            }, function(response) {
                                // error handling
                                viewGame.formSessionErrors = response.response.data;
                            }
                    );
                },
                // update session
                updateSession: function() {
                    axios.put('/api/game-sessions/' + this.session.id, this.sessionForm)
                            .then(function(response) {
                                $("#modal-session").modal('hide');
                                toastr.info('Session updated successfully.', '', {timeOut: 1000});
                                viewGame.listSessionsForGame();
                            }, function(response) {
                                // error handling
                                viewGame.formSessionErrors = response.response.data;
                            }
                    );
                },
                // delete session
                deleteSession: function() {
                    axios.delete('/api/game-sessions/' + this.session.id).then(function(response) {
                        viewGame.session = {};
                        viewGame.listSessionsForGame();
                        toastr.info('Session deleted.', '', {timeOut: 1000});
                    });
                },
                // create player
                createPlayer: function() {
                    axios.post('/api/players', this.playerForm)
                            .then(function(response) {
                                viewGame.displaySession(viewGame.session.id);
                                $("#modal-player").modal('hide');
                                toastr.info('Player added successfully.', '', {timeOut: 1000});
                            }, function(response) {
                                // error handling
                                viewGame.formPlayerErrors = response.response.data;
                            }
                    );
                },
                // update player
                updatePlayer: function() {
                    axios.put('/api/players/' + this.playerForm.id, this.playerForm)
                            .then(function(response) {
                                viewGame.displaySession(viewGame.session.id);
                                $("#modal-player").modal('hide');
                                toastr.info('Player updated successfully.', '', {timeOut: 1000});
                                viewGame.listSessionsForGame();
                            }, function(response) {
                                // error handling
                                viewGame.formPlayerErrors = response.response.data;
                            }
                    );
                },
                // delete player
                deletePlayer: function(index) {
                    axios.delete('/api/players/' + this.session.players[index].id).then(function(response) {
                        viewGame.displaySession(viewGame.session.id);
                        toastr.info('Player deleted.', '', {timeOut: 1000});
                    });
                },
                // add photo
                addPhoto: function() {
                    // sending files is complicated
                    var fdata = new FormData();
                    fdata.append('photo', document.getElementById('photoInput').files[0]);
                    fdata.append('game_session_id', this.photoForm.game_session_id);
                    fdata.append('title', this.photoForm.title);

                    axios.post('/api/photos', fdata, {headers: {'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)}})
                            .then(function(response) {
                                viewGame.displaySession(viewGame.session.id);
                                $("#modal-photo").modal('hide');
                                toastr.info('Photo added successfully.', '', {timeOut: 1000});

                                // clear form
                                viewGame.photoForm.title = '';
                            }, function(response) {
                                // error handling
                                viewGame.formPhotoErrors = response.response.data;
                            }
                    );
                },
                // delete photo
                deletePhoto: function(id) {
                    axios.delete('/api/photos/' + id).then(function(response) {
                        viewGame.displaySession(viewGame.session.id);
                        toastr.info('Photo deleted.', '', {timeOut: 1000});
                    });
                }
            }

        });
    </script>
    {{--ekko lightbox--}}
    <script type="text/javascript" src="/js/ekko-lightbox.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
@stop
