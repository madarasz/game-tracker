<script type="text/javascript">
    var userDetails = new Vue({
        el: '#user-details',

        data: {
            userId: '{{ $userId }}',
            user: {},
            games: [],
            dataLoaded: false,
            seasonsToBeLoaded: 0
        },

        mounted: function() {
            this.loadUserDetails();
        },

        methods: {
            loadUserDetails: function() {
                axios.get('/api/user-details/'+this.userId).then(function (response) {
                    userDetails.user = response.data.user;
                    userDetails.games = response.data.games.sort((a, b) => (b.sessionCount - a.sessionCount));
                    // select latest season
                    userDetails.games.map(x => { 
                        if (x.seasons.length > 0) {
                            x.selectedSeasonId = x.seasons[0].id;
                        } else {
                            x.selectedSeasonId = null;
                        }
                        return x;
                    });
                    // check if there are sessions without season
                    userDetails.seasonsToBeLoaded = response.data.games.filter(x => x.sessionsWithoutSeason > 0).length;
                    userDetails.factionsToBeLoaded = response.data.games.filter(x => x.factionCount > 0).length;
                    if (userDetails.seasonsToBeLoaded + userDetails.factionsToBeLoaded> 0) {
                        userDetails.loadAdditionalData();
                    } else {
                        userDetails.dataLoaded = true;
                    }
                });
            },
            // load rankings without seasons and factions
            loadAdditionalData: function() {
                for (var i = 0; i < this.games.length; i++) {
                    // rankings without seasons
                    if (this.games[i].sessionsWithoutSeason > 0) {
                        axios.get('/api/games/'+this.games[i].id+'/0/ranking').then(function (response) {
                            var gameId = parseInt(/(?:\/api\/games\/)([\d]*)/g.exec(response.config.url)[1]);
                            var game_ = userDetails.games.filter(x => x.id == gameId)[0];
                            var season = { 
                                id: null,
                                title: 'without season',
                                sessionCount: game_.sessionsWithoutSeason,
                                points: response.data 
                            
                            };
                            game_.seasons.push(season);
                            // check if all is loaded
                            if (--userDetails.seasonsToBeLoaded < 1) {
                                userDetails.dataLoaded = true;
                            }
                            
                        });
                    }
                    // factions
                    if (this.games[i].factionCount > 0) {
                        axios.get('/api/user-details/'+this.userId+'/factions/'+this.games[i].id).then(function (response) {
                            var gameId = parseInt(/([\d]*)$/g.exec(response.config.url)[1]);
                            var game_ = userDetails.games.filter(x => x.id == gameId)[0];
                            game_.factionStats = response.data;
                            // check if all is loaded
                            if (--userDetails.seasonsToBeLoaded < 1) {
                                userDetails.dataLoaded = true;
                            }
                        });
                    }
                }
            },
            getSeason: function(gameId, seasonId) {
                return this.games.filter(x => x.id == gameId)[0]
                    .seasons.filter(y => y.id == seasonId)[0];
            },
            getPointsInSeason: function(gameId, seasonId) {
                return this.getSeason(gameId, seasonId)
                    .points.filter(z => z.user_id == this.userId)[0];
            },
            getSortedPoints: function(gameId, seasonId) {
                return this.getSeason(gameId, seasonId).points.sort((a,b) => b.points - a.points);
            },
            getUserRank: function(gameId, seasonId) {
                var sortedPoints = this.getSortedPoints(gameId, seasonId);
                for (var i = 0; i < sortedPoints.length; i++) {
                    if (sortedPoints[i].user_id == this.userId) {
                        return i+1;
                    }
                }
            },
            getUserSessionCount: function(gameId) {
                var seasons = this.games.filter(x => x.id == gameId)[0].seasons;
                var count = 0;
                for (var i = 0; i < seasons.length; i++) {
                    var point = seasons[i].points.filter(y => y.user_id == this.userId)[0];
                    if (point) {
                        count += point.sessionCount;
                    }
                    
                }
                return count;
            },
            selectSeason: function(gameId, seasonId) {
                var gameIndex = -1;
                var game_;
                for (var i = 0; i < this.games.length; i++) {
                    if (this.games[i].id == gameId) {
                        gameIndex = i;
                        game_ = this.games[i];
                        break;
                    }
                }
                game_.selectedSeasonId = seasonId;
                Vue.set(this.games, gameIndex, game_);                
            }
        }
    });

    // EKKO Lightbox
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
</script>