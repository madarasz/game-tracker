<script type="text/javascript">
    var userDetails = new Vue({
        el: '#user-details',

        data: {
            userId: '{{ $userId }}',
            user: {},
            games: []
        },

        mounted: function() {
            this.loadUserDetails();
        },

        methods: {
            loadUserDetails: function() {
                axios.get('/api/user-details/'+this.userId).then(function (response) {
                    userDetails.user = response.data.user;
                    userDetails.games = response.data.games.sort((a,b) => (b.sessionCount - a.sessionCount));
                    // add ranking for sessions without seasons
                    for (var i = 0; i < userDetails.games.length; i++) {
                        userDetails.games[i].selectedSeasonId = -1;
                        if (userDetails.games[i].sessionsWithoutSeason > 0) {
                            axios.get('/api/games/'+userDetails.games[i].id+'/0/ranking').then(function (response) {
                                var regex = /(?:\/api\/games\/)([\d]*)/g;
                                var gameId = parseInt(regex.exec(response.config.url)[1]);
                                var game_ = userDetails.games.filter(x => x.id == gameId)[0];
                                var season = { 
                                    id: null,
                                    title: 'without season',
                                    sessionCount: game_.sessionsWithoutSeason,
                                    points: response.data 
                                };
                                
                                game_.seasons.push(season);
                                //userDetails.games.[i].seasons.push(season);
                            });
                        }
                    }
                });
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
</script>