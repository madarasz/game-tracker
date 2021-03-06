<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="/js/ekko-lightbox.min.js"></script>
<script type="text/javascript">

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return false;
    }

    var viewGame = new Vue({
        el: '#game-viewer',

        data: {
            id: '{{ $id }}',
            game: {},
            users: [],
            session: {},
            sessionForm: {},
            seasonForm: {},
            factionForm: {},
            pointForm: { score: []},
            ranking: [],
            sessionList: [],
            seasonList: [],
            factionList: [],
            requestedSeasonId: null,
            formSessionErrors: [],
            formSeasonErrors: [],
            modalSessionTitle: '',
            modalSessionButton: '',
            modalSeasonTitle: '',
            modalFactionTitle: '',
            modalFactionButton: '',
            sessionEditMode: false,
            factionEditMode: false,
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
            confirmText: '',
            randomFactionId: 0,
            factionWinrateLoaded: false,
            sortByWinrate: false
        },

        mounted: function() {
            google.charts.load('current', {'packages':['corechart']});

            this.loadGame();
            this.loadUsers();

            // load specific session
            @if ($session)
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
            // prepare modal for create season
            modalSeasonForCreate: function() {
                this.seasonForm = { game_id: '{{ $id }}', start_date: this.defaultDate(), end_date: this.defaultDate()};
                this.formSeasonErrors = [];
                this.modalSeasonTitle = 'Create season for ' + this.game.title;
            },
            // prepare modal for create faction
            modalFactionForCreate: function() {
                this.factionForm = { game_id : '{{ $id }}', iconFile: null, photo_id: null };
                this.formFactionErrors = [];
                this.modalFactionTitle = 'Create faction for ' + this.game.title;
                this.modalFactionButton = 'Create';
                this.factionEditMode = false;
            },
            // prepare modal for edit faction
            modalFactionForEdit: function(index) {
                this.factionForm = this.factionList[index];
                this.modalFactionTitle = 'Edit faction';
                this.modalFactionButton = 'Save';
                this.factionEditMode = true;
                $("#modal-faction").modal('show');
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
                this.playerForm.score = 0;
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
                    viewGame.seasonList = viewGame.game.seasons;
                    viewGame.factionList = viewGame.game.factions.sort((a,b) => (b.elo - a.elo));
                    // decide on which season to request
                    if (viewGame.game.activeSeason && viewGame.requestedSeasonId == null) {
                        viewGame.requestedSeasonId = viewGame.game.activeSeason.id;
                    } else if (viewGame.requestedSeasonId == null) {
                        viewGame.requestedSeasonId = 0;
                    }
                    // elo history chart
                    if (viewGame.game.sessionCount > 0) {
                        viewGame.loadEloHistory();
                    }
                    viewGame.loadRankings();
                    viewGame.listSessionsForGame();
                    viewGame.loadFactionWinrates();
                });
            },
            // list sessions for game
            listSessionsForGame: function() {
                axios.get('/api/game-sessions/game/' + this.id + '/' + this.requestedSeasonId).then(function (response) {
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
                axios.get('/api/games/' + this.id + '/' + this.requestedSeasonId + '/ranking').then(function (response) {
                    viewGame.ranking = response.data;
                });
            },
            // loads ELO history for game and displays chart
            loadEloHistory: function() {
                axios.get('/api/ranking/game/' + this.id + '/' + this.requestedSeasonId).then(function (response) {
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
                    viewGame.displaySeason(response.data.season_id);
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
                            viewGame.displaySession(viewGame.session.id); // season reason
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
            addPhoto: function(uploadForSession = true, photoInputID = 'photoInput') {
                // sending files is complicated
                var fdata = new FormData();
                fdata.append('photo', document.getElementById(photoInputID).files[0]);
                if (uploadForSession) {
                    fdata.append('game_session_id', this.photoForm.game_session_id);
                    fdata.append('title', this.photoForm.title);
                }

                axios.post('/api/photos', fdata, {headers: {'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)}})
                        .then(function(response) {
                            if (uploadForSession) {
                                viewGame.displaySession(viewGame.session.id);
                                viewGame.listSessionsForGame();
                                $("#modal-photo").modal('hide');
                            } else {
                                if (photoInputID == 'logoInput') {
                                    viewGame.factionForm.iconFile = response.data.url;
                                    viewGame.factionForm.photo_id = response.data.id;
                                } else {
                                    viewGame.factionForm.factionFile = response.data.url;
                                    viewGame.factionForm.big_photo_id = response.data.id;
                                }
                            }
                            toastr.info('Photo added successfully.', '', {timeOut: 1000});

                            // clear form
                            viewGame.photoForm.title = '';
                        }, function(response) {
                            // error handling
                            viewGame.formPhotoErrors = response.response.data;
                            toastr.error('Problem uploading image.', '', {timeOut: 1000});
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
            // rotate photo
            rotatePhoto: function(id, dir) {
                axios.get('/api/photos/' + id + '/rotate/' + dir).then(function (response) {
                    viewGame.displaySession(viewGame.session.id);
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
                axios.get('/api/games/' + viewGame.id + '/' + this.requestedSeasonId + '/ranking/recalculate').then(
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
            },
            // creates season
            createSeason: function() {
                axios.post('/api/game-seasons', this.seasonForm)
                        .then(function(response) {
                            viewGame.loadGame();
                            $("#modal-season").modal('hide');
                            toastr.info('Season created successfully.', '', {timeOut: 1000});
                            // reload session if necessary
                            if (typeof(viewGame.session.id) !== 'undefined') {
                                viewGame.displaySession(viewGame.session.id);
                            }
                        }, function(response) {
                            // error handling
                            viewGame.formSeasonErrors = response.response.data;
                        }
                );
            },
            // deletes season
            deleteSeason: function(id) {
                axios.delete('/api/game-seasons/' + id).then(function (response) {
                    viewGame.loadGame();
                    toastr.info('Session deleted.', '', {timeOut: 1000});
                    // reload session if necessary
                    if (typeof(viewGame.session.id) !== 'undefined') {
                        viewGame.displaySession(viewGame.session.id);
                    }
                    // reload sessions, ranking, chart
                    viewGame.displaySeason(0);
                });
            },
            // displays season
            displaySeason: function(id) {
                viewGame.requestedSeasonId = id;
                viewGame.listSessionsForGame();
                viewGame.loadEloHistory();
                viewGame.loadRankings();
            },
            // returns faction having ID
            getFactionById: function(id) {
                return this.factionList.filter(fac => fac.id == id)[0];
            },
            // creates faction
            createFaction: function() {
                axios.post('/api/game-factions', this.factionForm)
                        .then(function(response) {
                            viewGame.loadGame();
                            $("#modal-faction").modal('hide');
                            toastr.info('Faction created successfully.', '', {timeOut: 1000});
                        }, function(response) {
                            // error handling
                            toastr.error('Problem with creating faction.', '', {timeOut: 1000});
                        }
                );
            },
            // updates faction
            updateFaction: function() {
                axios.put('/api/game-factions/' + this.factionForm.id, this.factionForm)
                        .then(function(response) {
                            viewGame.loadGame();
                            $("#modal-faction").modal('hide');
                            toastr.info('Faction updated successfully.', '', {timeOut: 1000});
                        }, function(response) {
                            // error handling
                            toastr.error('Problem with updating faction.', '', {timeOut: 1000});
                        }
                );
            },
            // deletes faction
            deleteFaction: function(id) {
                axios.delete('/api/game-factions/' + id)
                    .then(function (response) {
                        viewGame.loadGame();
                        toastr.info('Faction deleted.', '', {timeOut: 1000});
                    }, function(response) {
                        toastr.error('Problem with deleting faction.', '', {timeOut: 1000});
                    }
                );
            },
            // removes faction icon
            removeFactionIcon: function() {
                this.factionForm.iconFile = null;
                this.factionForm.photo_id = null;
            },
            // removes faction photo
            removeFactionPhoto: function() {
                this.factionForm.factionFile = null;
                this.factionForm.big_photo_id = null;
            },
            // faction changed on player form
            playerFactionChanged: function() {
                if (this.playerForm.notes == null) {
                    this.playerForm.notes = this.getFactionById(this.playerForm.faction_id).name;
                }
            },
            // counts how many times a player played in the season
            countPlayerSession: function(userId) {
                var count = 0;
                for (var i = 0; i < this.sessionList.length; i++) {
                    for (var u = 0; u < this.sessionList[i].players.length; u++)
                        if (this.sessionList[i].players[u].user.id == userId) {
                            count++;
                            break;
                        }
                }
                return count;
            },
            // random faction
            randomFaction: function() {
                this.randomFactionId = Math.floor(Math.random()*this.factionList.length);
            },
            loadFactionWinrates: function() {
                axios.get('/api/games/winrate/' + this.id).then(function (response) {
                    for (var i = 0; i < response.data.length; i++) {
                        viewGame.factionList.find(faction => faction.id == response.data[i].id).winrate = response.data[i]
                    }
                    viewGame.factionWinrateLoaded = true;
                    viewGame.sortByWinrate = getCookie('sortByWinrate')
                    viewGame.sortFactions()
                })
            },
            getFactionDetails: function(factionId) {
                if (!this.factionWinrateLoaded) return "";
                const faction = this.factionList.find(faction => faction.id == factionId)
                result = "<strong>elo:</strong> " + faction.elo
                result += " <strong>winrate:</strong> " + this.formatPerc(faction.winrate.winrate)
                result += " (" + faction.winrate.sessionCount + ")"
                result += '<table width="100%" border="1px black solid"><tr>'
                for (const [key, value] of Object.entries(faction.winrate.winratePerPlayerNumber)) {
                    result += '<td align="center"><i>' + key + " players</i><br/>" + this.formatPerc(value.winrate) + " (" + value.sessionCount + ")</td>"
                }
                result += "</tr></table>"
                return result
            },
            formatPerc: function(value) {
                return parseFloat(value * 100).toFixed(1)+"%"
            },
            changeFactionSort: function() {
                this.sortByWinrate = !this.sortByWinrate
                setCookie('sortByWinrate', this.sortByWinrate, 30)
                this.sortFactions()
            },
            sortFactions: function() {
                if (this.sortByWinrate) {
                    this.game.factions.sort((a,b) => (b.winrate.winrate - a.winrate.winrate) || (b.winrate.sessionCount - a.winrate.sessionCount));
                } else {
                    this.game.factions.sort((a,b) => (b.elo - a.elo));
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