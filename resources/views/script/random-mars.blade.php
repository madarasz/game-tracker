<script type="text/javascript">
    var randomMars = new Vue({
        el: '#random-mars',
        data: {
            b64: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
            playerNumber: 2,
            showResults: false,
            copiedMessage: false,
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
            // checks for state in URL
            var state = window.location.href.match(/r=(.*)/);
            if (state != null) {
                if (state[1].indexOf('&') > 1) {
                    state = state[1].substr(0, state[1].indexOf('&'));
                } else {
                    state = state[1];
                }
                this.decodeValues(state);
            }
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
            },
            corporationNum: function() {
                return this.playerNumber + 2;
            },
            milestoneNum: function() {
                return this.chosenBoard == 'Hellas' ? 5 : 6;
            },
            awardNum: function() {
                return this.chosenBoard == 'Elysium' ? 5 : 6;
            }
        },
        methods: {
            filename: function(name) {
                return name.toLowerCase().replace(/ /g,"-");
            },
            randomise: function() {
                this.randomBoard(false);
                this.randomCorps();
                this.randomColonies();
                this.showResults = true;
                this.encodeValues();
            },
            randomBoard: function(encode = true) {
                this.boardMapping = Math.floor(Math.random() * this.boards.length);
                this.randomMilestones();
                this.randomAwards();
                if (encode) {
                    this.encodeValues();
                }
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
                this.encodeValues();
            },
            randomColonies: function() {
                this.colonyMapping = this.randomElements(this.playerNumber+2, this.colonies.length);
            },
            randomColony: function(index) {
                Vue.set(this.colonyMapping, index, this.randomOneElement(this.colonyMapping, this.colonies.length));
                this.encodeValues();
            },
            randomMilestones: function() {
                this.milestoneMapping = this.randomElements(this.milestoneNum, this.milestones.length);
            },
            randomMilestone: function(index) {
                Vue.set(this.milestoneMapping, index, this.randomOneElement(this.milestoneMapping, this.milestones.length));
                this.encodeValues();
            },
            randomAwards: function() {
                this.awardMapping = this.randomElements(this.awardNum, this.awards.length);
            },
            randomAward: function(index) {
                Vue.set(this.awardMapping, index, this.randomOneElement(this.awardMapping, this.awards.length));
                this.encodeValues();
            },
            // encodes an array to base64
            encodeArray: function(mapping) {
                return mapping.map(i => this.b64[i]).join('');
            },
            // encodes mapping arrays to URL
            encodeValues: function() {
                var url = '/random-mars?r=' + this.playerNumber + this.boardMapping + 
                    this.encodeArray(this.corporationMapping) + this.encodeArray(this.milestoneMapping) +
                    this.encodeArray(this.awardMapping) + this.encodeArray(this.colonyMapping);
                history.replaceState('', 'Mars Randomizer', url);
                this.copiedMessage = false;
            },
            // decodes an array from base64
            decodeArray: function(bmap) {
                var result = [];
                for (var i = 0; i < bmap.length; i++) {
                    result.push(this.b64.indexOf(bmap[i]));
                }
                return result;
            },
            // decodes mapping arrays fro URL
            decodeValues: function(state) {
                this.playerNumber = parseInt(state[0]);
                this.boardMapping = parseInt(state[1]);      
                this.corporationMapping = this.decodeArray(state.substring(2, 2+this.corporationNum));
                this.milestoneMapping = this.decodeArray(state.substring(2+this.corporationNum, 2+this.corporationNum+this.milestoneNum));
                this.awardMapping = this.decodeArray(state.substring(2+this.corporationNum+this.milestoneNum, 2+this.corporationNum+this.milestoneNum+this.awardNum));
                this.colonyMapping = this.decodeArray(state.substring(2+this.corporationNum+this.milestoneNum+this.awardNum));
                this.showResults = true;
            },
            // copies URL to clipboard
            copyUrl: function() {
                const el = document.createElement('textarea');
                el.value = location.href;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                this.copiedMessage = true;
            }
        }
    })
</script>