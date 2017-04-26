<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="/js/ekko-lightbox.min.js"></script>
<script type="text/javascript">
    var viewGame = new Vue({
        el: '#game-viewer',

        data: {
            id: '{{ $id }}',
            game: {},
            users: [],
            session: {},
            sessionForm: {},
            pointForm: { score: []},
            ranking: [],
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
            formPhotoErrors: [],
            eloHistory : {},
            chart: {},
            dataTable: {},
            chartOptions: {},
            confirmCallback: function() {},
            confirmText: ''
        },

        mounted: function() {
            google.charts.load('current', {'packages':['corechart']});

            this.loadGame();
            this.loadRankings();
            this.listSessionsForGame();
            this.loadUsers();

            @if ($session)
            // load specific session
            this.displaySession({{ $session }});
            @endif
        },

        methods: {
            // prepare modal for create session
            modalSessionForCreate: function() {
                this.sessionForm = { game_id: '{{ $id }}', date: this.defaultDate()};
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
            // prepare modal for mass point edit
            modalPoints: function() {
                this.pointForm.game_session_id = this.session.id;
                for (var i = 0; i < this.session.players.length; i++) {
                    this.pointForm.score[i].v = this.session.players[i].score;
                }
            },

            // generates date string for today
            defaultDate: function() {
                var today = new Date(),
                    dd = today.getDate(),
                    mm = today.getMonth()+1, //January is 0!
                    yyyy = today.getFullYear();

                if(dd<10) {
                    dd='0'+dd
                }

                if(mm<10) {
                    mm='0'+mm
                }

                return yyyy + '-' + mm + '-' + dd;
            },

            // loads basic info about the game
            loadGame: function() {
                axios.get('/api/games/' + this.id).then(function (response) {
                    viewGame.game = response.data;
                    // elo history chart
                    if (viewGame.game.sessionCount > 0) {
                        viewGame.loadEloHistory();
                    }
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
            // load rankings
            loadRankings: function() {
                axios.get('/api/games/' + this.id + '/ranking').then(function (response) {
                    viewGame.ranking = response.data;
                });
            },
            // loads ELO history for game and displays chart
            loadEloHistory: function() {
                axios.get('/api/ranking/game/' + this.id).then(function (response) {
                    viewGame.eloHistory = response.data;
                    google.charts.setOnLoadCallback(viewGame.drawELOChart);
                });
            },
            // display session
            displaySession: function(id) {
                axios.get('/api/game-sessions/' + id).then(function (response) {
                    // initialize mass points edit form
                    for (var i = 0; i < response.data.players.length; i++) {
                        viewGame.pointForm.score.push({v: 0});
                    }

                    viewGame.session = response.data;
                    $(window).scrollTop(0);
                });

                // updating URL in address bar
                history.replaceState(null, 'Gametracker', '/games/' + this.id + '/session/' + id);
            },
            // creates session
            createSession: function() {
                axios.post('/api/game-sessions', this.sessionForm)
                        .then(function(response) {
                            viewGame.displaySession(response.data.id);
                            viewGame.listSessionsForGame();
                            viewGame.game.sessionCount++;
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
                axios.delete('/api/game-sessions/' + this.session.id).then(function (response) {
                    viewGame.session = {};
                    viewGame.listSessionsForGame();
                    viewGame.game.sessionCount--;
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
                axios.delete('/api/players/' + this.session.players[index].id).then(function (response) {
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
                            viewGame.listSessionsForGame();
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
                axios.delete('/api/photos/' + id).then(function (response) {
                    viewGame.displaySession(viewGame.session.id);
                    viewGame.listSessionsForGame();
                    toastr.info('Photo deleted.', '', {timeOut: 1000});
                });
            },
            // negates winner flag for player
            toggleWinner: function(index) {
                axios.put('/api/players/' + this.session.players[index].id,
                        {
                            'user_id' : this.session.players[index].user.id,
                            'score': this.session.players[index].score,
                            'winner': 1 - this.session.players[index].winner // negate flag
                        })
                        .then(function(response) {
                            viewGame.displaySession(viewGame.session.id);
                            viewGame.listSessionsForGame();
                        }
                );
            },
            // conclude session, calculate
            concludeSession: function() {
                axios.get('/api/game-sessions/' + this.session.id + '/conclude').then(function (response) {
                    viewGame.displaySession(viewGame.session.id);
                    viewGame.loadRankings();
                    viewGame.listSessionsForGame();
                    viewGame.loadEloHistory();
                    toastr.info('Session concluded.', '', {timeOut: 1000});
                });
            },
            // copy from existing session
            cloneSession : function(id) {
                axios.get('/api/game-sessions/' + id + '/clone').then(function (response) {
                    viewGame.displaySession(response.data.id);
                    viewGame.loadRankings();
                    viewGame.listSessionsForGame();
                    viewGame.game.sessionCount++;
                    toastr.info('Session cloned.', '', {timeOut: 1000});
                    $(window).scrollTop(0);
                });
            },
            // mass edit of points
            pointMassEdit: function() {
                var calls = [], max = -9999;

                // get best score
                for (var i = 0; i < this.session.players.length; i++) {
                    if (parseInt(this.pointForm.score[i].v) > max) {
                        max = parseInt(this.pointForm.score[i].v);
                    }
                }

                // multiple calls
                for (var i = 0; i < this.session.players.length; i++) {
                    this.session.players[i].score = this.pointForm.score[i].v;
                    this.session.players[i].user_id = this.session.players[i].user.id;
                    this.session.players[i].winner = this.session.players[i].score == max ? 1 : 0;
                    calls.push(axios.put('/api/players/' + this.session.players[i].id, this.session.players[i]));
                }

                axios.all(calls).then(function() {
                        viewGame.displaySession(viewGame.session.id);
                        viewGame.listSessionsForGame();
                        $("#modal-points").modal('hide');
                        toastr.info('Player scores updated successfully.', '', {timeOut: 1000});
                    }, function() {
                        viewGame.displaySession(viewGame.session.id);
                        viewGame.listSessionsForGame();
                        toastr.error('Error. Scores should be integer values.', '', {timeOut: 1000});
                    }
                );
            },
            // draws ELO History chart
            drawELOChart: function() {
                // data header
                var data = [['date'], ['init']];
                for (var i = 0; i < this.eloHistory.user_list.length; i++) {
                    data[0].push(this.users[this.eloHistory.user_list[i] - 1].name);
                    data[1].push(1500);
                }

                // data
                for (var i = 0; i < this.eloHistory.history.length; i++) {
                    var row = [this.eloHistory.history[i].date];
                    for (var u = 0; u < this.eloHistory.user_list.length; u++) {
                        row.push(this.eloHistory.history[i].player_scores[this.eloHistory.user_list[u]]);
                    }
                    data.push(row);
                }

                this.dataTable = google.visualization.arrayToDataTable(data);

                this.chartOptions = {
                    curveType: 'none',
                    legend: { position: 'right' },
                    chartArea: {  width: $('#chart-elo-history').width() - 180, height: "90%", left: 50 },
                    vAxis: { baseline: 1500, viewWindowMode: 'maximized' },
                    height: 200
                };

                this.chart = new google.visualization.LineChart(document.getElementById('chart-elo-history'));

                this.chart.draw(this.dataTable, this.chartOptions);
            },
            // recalculates ELO rankings for game
            recalculateELO: function() {
                axios.get('/api/games/' + viewGame.id + '/ranking/recalculate').then(
                        function (response) {
                            if (viewGame.session.id !== undefined) {
                                viewGame.displaySession(viewGame.session.id);
                            }
                            viewGame.loadRankings();
                            viewGame.drawELOChart();
                            toastr.info('Rankings recalculated.', '', {timeOut: 1000});
                        }, function (response) {
                            toastr.error('Something went wrong', '', {timeOut: 1000});
                        }
                );
            },
            // un-concludes session
            unconcludeSession: function() {
                if (confirm('conf')) {
                    axios.put('/api/game-sessions/' + this.session.id, {
                            concluded: 0,
                            date: viewGame.session.date,
                            place: viewGame.session.place
                        }).then(function(response) {
                            viewGame.displaySession(viewGame.session.id);
                            toastr.info('Session unlocked.', '', {timeOut: 1000});
                        }, function(response) {
                            // error handling
                            toastr.error('There was a problem.', '', {timeOut: 1000});
                        }
                    );
                }
            }
        }

    });

    //create trigger to resizeEnd event
    $(window).resize(function() {
        if(this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function() {
            $(this).trigger('resizeEnd');
        }, 500);
    });

    //redraw graph when window resize is completed
    $(window).on('resizeEnd', function() {
        viewGame.chartOptions.chartArea.width = $('#chart-elo-history').width() - 180;
        viewGame.chart.draw(viewGame.dataTable, viewGame.chartOptions);
    });

    // EKKO Lightbox
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

</script>