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
                            Player number:
                            <select v-model="playerNumber">
                                <option v-for="n in 4" :value="n+1">@{{ n+1 }}</option>
                            </select>
                            <a class="btn btn-sm btn-primary text-white" @click="randomise">Randomise</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--Results--}}
        <div class="row mt-3" v-if="showResults">
            <div class="col-sm-6 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Board</h5>
                        <table style="width: 100%">
                            <tr>
                                <td>
                                    <em>@{{ chosenBoard }}</em>
                                </td>
                                <td class="text-right">
                                    <a @click="randomBoard"><i class="fa fa-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Corporations</h5>
                        <table style="width: 100%">
                            <tr v-for="(corporation, index) in chosenCorporations" :style="index > 0 ? 'border-top: 1px solid black;' : ''">
                                <td>
                                    <em>@{{ corporation }}</em>
                                </td>
                                <td class="text-right">
                                    <a @click="randomCorp(index)"><i class="fa fa-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Milestones</h5>
                        <table style="width: 100%">
                            <tr v-if="chosenBoard == 'Hellas'" style="border-bottom: 1px solid black">
                                <td colspan="2">
                                    <strong>polar explorer</strong>
                                <td>
                            </tr>
                            <tr v-for="(milestone, index) in chosenMilestones" :style="index > 0 ? 'border-top: 1px solid black;' : ''">
                                <td>
                                    <em>@{{ milestone }}</em>
                                </td>
                                <td class="text-right">
                                    <a @click="randomMilestone(index)"><i class="fa fa-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                        <h5 class="card-title mt-3">Awards</h5>
                        <table style="width: 100%">
                            <tr v-if="chosenBoard == 'Elysium'" style="border-bottom: 1px solid black">
                                <td colspan="2">
                                    <strong>desert settler</strong>
                                <td>
                            </tr>
                            <tr v-for="(award, index) in chosenAwards" :style="index > 0 ? 'border-top: 1px solid black;' : ''">
                                <td>
                                    <em>@{{ award }}</em>
                                </td>
                                <td class="text-right">
                                    <a @click="randomAward(index)"><i class="fa fa-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="card">
                    <div class="card-block">    
                        <h5 class="card-title">Colonies</h5>
                        <table style="width: 100%">
                            <tr v-for="(colony, index) in chosenColonies" :style="index > 0 ? 'border-top: 1px solid black;' : ''">
                                <td>
                                    <em>@{{ colony }}</em>
                                </td>
                                <td class="text-right">
                                    <a @click="randomColony(index)"><i class="fa fa-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    @include('script/random-mars')
@stop