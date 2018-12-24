<script type="text/javascript">
    var randomMars = new Vue({
        el: '#random-mars',
        data: {
            playerNumber: 2,
            showResults: false,
            // arrays to randomize from
            corporations: [], // from API
            boards: ['base game', 'Hellas', 'Elysium'],
            colonies: ['Luna', 'Ceres', 'Triton', 'Ganymede', 'Calisto', 'Io', 'Europa', 'Enceladus', 'Miranda', 'Titan', 'Pluto'],
            milestones: [
                'terraformer', 'mayor', 'gardener', 'builder', 'planner', 
                'generalist', 'specialist', 'ecologist', 'tycoon', 'legend',
                'diversifier', 'tactican', 'energizer', 'rim settler', // + polar explorer
                'floaters'
            ],
            awards: [
                'landlord', 'banker', 'scientist', 'thermalist', 'miner',
                'celebrity', 'industrialist', 'estate dealer', 'benefactor', // + desert settler
                'cultivator', 'magnate', 'space baron', 'excentric', 'contractor',
                'venus'
            ],
            // which indexes are selected
            boardMapping: 0,
            corporationMapping: [],
            milestoneMapping: [],
            awardMapping: [],
            colonyMapping: []
        },
        mounted: function() {
            // get Mars Corporations from API
            axios.get('/api/games/7').then(function (response) {
                randomMars.corporations = response.data.factions.map(obj => ({name: obj.name, icon: obj.iconFile}));
            });
        },
        computed: {
            chosenBoard: function() {
                return this.boards[this.boardMapping];
            },
            chosenCorporations: function() {
                return this.corporationMapping.map(i => this.corporations[i]);
            },
            chosenColonies: function() {
                return this.colonyMapping.map(i => this.colonies[i]);
            },
            chosenMilestones: function() {
                return this.milestoneMapping.map(i => this.milestones[i]);
            },
            chosenAwards: function() {
                return this.awardMapping.map(i => this.awards[i]);
            }
        },
        methods: {
            filename: function(name) {
                return name.toLowerCase().replace(/ /g,"-");
            },
            randomise: function() {
                this.randomBoard();
                this.randomCorps();
                this.randomColonies();
                this.showResults = true;
            },
            randomBoard: function() {
                this.boardMapping = Math.floor(Math.random() * this.boards.length);
                this.randomMilestones();
                this.randomAwards();
            },
            // returns array of X elements from [0, 1, ..., n-1] array
            randomElements: function(x, n) {
                var result = [];
                while (result.length < x) {
                    var element = Math.floor(Math.random()*n);
                    if(result.indexOf(element) < 0) result.push(element);
                }
                return result;
            },
            // returns a random element which is not present in mapping array
            randomOneElement: function(mapping, max) {
                var n;
                do {
                    n = Math.floor(Math.random()*max);
                } while (mapping.indexOf(n) > -1);
                return n;
            },
            randomCorps: function() {
                this.corporationMapping = this.randomElements(this.playerNumber+2, this.corporations.length);
            },
            randomCorp: function(index) {
                Vue.set(this.corporationMapping, index, this.randomOneElement(this.corporationMapping, this.corporations.length));
            },
            randomColonies: function() {
                this.colonyMapping = this.randomElements(this.playerNumber+2, this.colonies.length);
            },
            randomColony: function(index) {
                Vue.set(this.colonyMapping, index, this.randomOneElement(this.colonyMapping, this.colonies.length));
            },
            randomMilestones: function() {
                var len = this.chosenBoard == 'Hellas' ? 5 : 6;
                this.milestoneMapping = this.randomElements(len, this.milestones.length);
            },
            randomMilestone: function(index) {
                Vue.set(this.milestoneMapping, index, this.randomOneElement(this.milestoneMapping, this.milestones.length));
            },
            randomAwards: function() {
                var len = this.chosenBoard == 'Elysium' ? 5 : 6;
                this.awardMapping = this.randomElements(len, this.awards.length);
            },
            randomAward: function(index) {
                Vue.set(this.awardMapping, index, this.randomOneElement(this.awardMapping, this.awards.length))
            },
        }
    })
</script>