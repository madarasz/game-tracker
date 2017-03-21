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
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <h5>Players</h5>
                            <em style="font-size: 80%">not yet developed</em>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <h5>Photos</h5>
                            <em style="font-size: 80%">not yet developed</em>
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
            <div class="card"/>
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
                                    <span v-if="formErrors['date']" class="error text-danger">@{{ formErrors['date'].toString() }}</span>
                                </div>
                            </div>

                            {{--Place--}}
                            <div class="form-group row">
                                <label for="place" class="col-sm-3 col-form-label">Place:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="place" class="form-control" v-model="sessionForm.place" />
                                    <span v-if="formErrors['place']" class="error text-danger">@{{ formErrors['place'].toString() }}</span>
                                </div>
                            </div>

                            {{--Notes--}}
                            <div class="form-group">
                                <label for="notes">Notes:</label>
                                <textarea name="notes" class="form-control" v-model="sessionForm.notes"></textarea>
                                <span v-if="formErrors['notes']" class="error text-danger">@{{ formErrors['notes'].toString() }}</span>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success">@{{ modalSessionButton }}</button>
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
        var viewGame = new Vue({
            el: '#game-viewer',

            data: {
                id: '{{ $id }}',
                game: {},
                session: {},
                sessionForm: {},
                sessionList: [],
                formErrors: [],
                modalSessionTitle: '',
                modalSessionButton: '',
                sessionEditMode: false
            },

            mounted: function() {
                this.loadGame();
                this.listSessionsForGame();
            },

            methods: {
                // prepare modal for create session
                modalSessionForCreate: function() {
                    this.sessionForm = { game_id: '{{ $id }}', date: ''};
                    this.formErrors = [];
                    this.modalSessionTitle = 'Create session';
                    this.modalSessionButton = 'Create';
                    this.sessionEditMode = false;
                },
                // prepate modal for edit session
                modalSessionForEdit: function() {
                    this.sessionForm = this.session;
                    this.formErrors = [];
                    this.modalSessionTitle = 'Edit session';
                    this.modalSessionButton = 'Save';
                    this.sessionEditMode = true;
                    $("#modal-session").modal('show');
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
                                $("#modal-session").modal('hide');
                                toastr.info('Session created successfully.', '', {timeOut: 1000});
                            }, function(response) {
                                // error handling
                                viewGame.formErrors = response.response.data;
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
                                viewGame.formErrors = response.response.data;
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
                }

            }

        });
    </script>
@stop