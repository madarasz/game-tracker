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
                            viewGame.listSessionsForGame();
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
                            viewGame.listSessionsForGame();
                            $("#modal-player").modal('hide');
                            toastr.info('Player updated successfully.', '', {timeOut: 1000});
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
                    viewGame.listSessionsForGame();
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
            },
            // negates winner flag for player
            toggleWinner: function(index) {
                axios.put('/api/players/' + this.session.players[index].id,
                        {
                            'user_id' : this.session.players[index].user.id,
                            'score': this.session.players[index].score,
                            'winner': 1 - this.session.players[index].winner
                        })
                        .then(function(response) {
                            viewGame.displaySession(viewGame.session.id);
                            viewGame.listSessionsForGame();
                        }
                );
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