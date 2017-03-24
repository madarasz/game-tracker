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
                this.formErrors = [];
                this.modalTitle = 'Edit Game';
                this.modalButton = 'Edit';
                this.editMode = true;
                $("#modal-game").modal('show');
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
                if (confirm('Delete the game?')) {
                    axios.delete('/api/games/' + id).then(function (response) {
                        manageGames.getGames();
                        toastr.info('Game deleted.', '', {timeOut: 1000});
                    });
                }
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