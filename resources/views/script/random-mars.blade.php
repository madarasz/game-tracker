<script type="text/javascript">
    var randomMars = new Vue({
        el: '#random-mars',
        data: {
            playerNumber: 2,
            corporations: [],
            boards: ['base game', 'Hellas', 'Elysium'],
            corporations: [],
            colonies: ['Luna', 'Ceres', 'Triton', 'Ganymede', 'Calisto', 'Io', 'Europa', 'Enceladus', 'Miranda', 'Titan', 'Pluto'],
            milestones: [
                'terraformer', 'mayor', 'gardener', 'builder', 'planner', 
                'generalist', 'specialist', 'ecologist', 'tycoon', 'legend',
                'diversifier', 'tactican', 'energizer', 'rim settler', // + polar explorer
                'floaters'
            ],
            awards: [
                'landlord', 'banker', 'scientist', 'global warmer', 'miner',
                'celebrity', 'industrialist', 'estate dealer', 'estate dealer', 'benefactor', // + desert settler
                'cultivator', 'magnate', 'space baron', 'excentric', 'contractor'
            ],
            chosenBoard: "",
            chosenCorporations: [],
            chosenColonies: [],
            chosenMilestones: [],
            chosenAwards: [],
            showResults: false
        },
        mounted: function() {
            axios.get('/api/games/7').then(function (response) {
                randomMars.corporations = response.data.factions.map(obj => obj.name);
            });
        },
        methods: {
            randomise: function() {
                this.randomBoard();
                this.randomCorps();
                this.randomColonies();
                this.showResults = true;
            },
            randomBoard: function() {
                this.chosenBoard = this.boards[Math.floor(Math.random() * this.boards.length)];
                this.randomMilestones();
                this.randomAwards();
            },
            randomCorps: function() {
                // magic
                this.chosenCorporations = this.corporations.map(x => ({ x, r: Math.random() })).sort((a,b) => a.r - b.r).map(a => a.x).slice(0, this.playerNumber+2);
            },
            randomCorp: function(index) {
                var difference = this.corporations.filter(x => !this.chosenCorporations.includes(x));
                Vue.set(this.chosenCorporations, index, difference[Math.floor(Math.random() * difference.length)]);
            },
            randomColonies: function() {
                // magic
                this.chosenColonies = this.colonies.map(x => ({ x, r: Math.random() })).sort((a,b) => a.r - b.r).map(a => a.x).slice(0, this.playerNumber+2);
            },
            randomColony: function(index) {
                var difference = this.colonies.filter(x => !this.chosenColonies.includes(x));
                Vue.set(this.chosenColonies, index, difference[Math.floor(Math.random() * difference.length)]);
            },
            randomMilestones: function() {
                var len = this.chosenBoard == 'Hellas' ? 5 : 6;
                // magic
                this.chosenMilestones = this.milestones.map(x => ({ x, r: Math.random() })).sort((a,b) => a.r - b.r).map(a => a.x).slice(0, len);
            },
            randomMilestone: function(index) {
                var difference = this.milestones.filter(x => !this.chosenMilestones.includes(x));
                Vue.set(this.chosenMilestones, index, difference[Math.floor(Math.random() * difference.length)]);
            },
            randomAwards: function() {
                var len = this.chosenBoard == 'Elysium' ? 5 : 6;
                // magic
                this.chosenAwards = this.awards.map(x => ({ x, r: Math.random() })).sort((a,b) => a.r - b.r).map(a => a.x).slice(0, len);
            },
            randomAward: function(index) {
                var difference = this.awards.filter(x => !this.chosenAwards.includes(x));
                Vue.set(this.chosenAwards, index, difference[Math.floor(Math.random() * difference.length)]);
            },
        }
    })
</script>