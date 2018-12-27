@extends('layout.general')

@section('content')
    <div class="mt-3" id="random-mars">
        {{--Header--}} 
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="card">
                    <div class="card-block">   
                    
                        <h4 class="card-title page-header">Terraforming Mars randomizer</h4>
                        <div>
                            <h5>Player number:
                                <select v-model="playerNumber">
                                    <option v-for="n in 4" :value="n+1">@{{ n+1 }}</option>
                                </select>
                            </h5>
                            <div class="pt-3" style="line-height: normal">
                                <a class="btn btn-primary text-white" @click="randomise">Randomize</a>
                                <a v-if="showResults" class="btn btn-success text-white" @click="copyUrl">Copy URL</a>
                                <small v-if="copiedMessage" style="vertical-align: middle; line-height: normal"><em>copied!</em></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--Results--}}
        <div class="row mt-3" v-if="showResults">
            <div class="col-md-6 col-xs-12 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Board</h5>
                        <table class="random-mars vmiddle">
                            <tr>
                                <td class="text-center">
                                    <img :src="'/img/random-mars/boards/'+filename(chosenBoard)+'.jpg'" :alt="chosenBoard" style="max-width: 50%"/>
                                    <div>
                                        <em>@{{ chosenBoard }}</em>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a @click="randomBoard"><i class="fa fa-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Corporations</h5>
                        <div class="random-mars-flex">
                            <div class="random-mars-flex-content" v-for="(corporation, index) in chosenCorporations">
                                <div class="random-mars-flex mb-3">
                                    <div class="random-mars-flex-content" style="width:100%">
                                        <img v-if="corporation && corporation.photo == null" :src="corporation.icon" style="max-width: 200px; max-height: 120px; width: 90%" />
                                        <a v-if="corporation && corporation.icon != null && corporation.photo != null" :href="corporation.photo" data-toggle="lightbox" data-gallery="gallery-faction-list">
                                            <img :src="corporation.icon" style="max-width: 200px; max-height: 120px; width: 90%" />
                                        </a>
                                        <a @click="randomCorp(index)"><i class="fa fa-refresh"></i></a>
                                    </div>
                                    <div v-if="corporation" class="random-mars-flex-content" style="width:100%">
                                        <em>@{{ corporation.name.match(/\(([^)]+)\)/)[1] }}</em>
                                    </div>
                                </div>                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Milestones</h5>
                        <div class="random-mars-flex">
                            <div v-if="chosenBoard == 'Hellas'" class="random-mars-flex-content">
                                <img src="/img/random-mars/milestones/polar-explorer.png" alt="polar explorer" />
                                <i class="fa fa-anchor text-primary"></i>
                            </div>
                            <div class="random-mars-flex-content" v-for="(milestone, index) in chosenMilestones">
                                <img :src="'/img/random-mars/milestones/'+filename(milestone)+'.png'" :alt="milestone" />
                                <a @click="randomMilestone(index)"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                        <h5 class="card-title mt-3">Awards</h5>
                        <div class="random-mars-flex">
                            <div v-if="chosenBoard == 'Elysium'" class="random-mars-flex-content">
                                <img src="/img/random-mars/awards/desert-settler.png" alt="desert settler" />
                                <i class="fa fa-anchor text-primary"></i>
                            </div>
                            <div class="random-mars-flex-content" v-for="(award, index) in chosenAwards">
                                <img :src="'/img/random-mars/awards/'+filename(award)+'.png'" :alt="award" />
                                <a @click="randomAward(index)"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Colonies</h5>
                        <div class="random-mars-flex">
                            <div class="random-mars-flex-content" v-for="(colony, index) in chosenColonies">
                            <a :href="'/img/random-mars/colonies/'+filename(colony)+'.png'" data-toggle="lightbox" data-gallery="gallery-colony-list">
                                <img :src="'/img/random-mars/colonies/'+filename(colony)+'.png'" :alt="colony" />
                            </a>
                                <a @click="randomColony(index)"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    @include('script/random-mars')
@stop