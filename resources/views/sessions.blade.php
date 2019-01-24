@extends('layout.general')

@section('content')
    <div class="row mt-3" id="game-viewer">
        <confirm-modal :modal-body="confirmText" :callback="confirmCallback"></confirm-modal>
        <div class="col-sm-12 col-lg-9 push-lg-3">
            {{--Session detais--}}
            <div class="card mb-3" v-if="session.date">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="card-title page-header">Session details</h4>
                        </div>
                        <div class="col-sm-6 text-right">
                            @if ($user)
                            <button type="button" class="btn btn-sm btn-primary" @click.prevent="modalSessionForEdit">
                                Edit
                            </button>
                            <confirm-button button-text="Delete" button-class="btn btn-sm btn-danger"
                                @click="confirmCallback = function() { deleteSession() }; confirmText = 'Delete session?'" />
                            @endif
                            <button type="button" class="close ml-1" aria-label="Close" @click.prevent="session = {}">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <strong>season:</strong>
                                <span v-if="session.season">
                                    @{{ session.season.title }}
                                    <em>(@{{ session.season.start_date }} - @{{ session.season.end_date }})</em>
                                </span>
                                <span v-if="!session.season"><em>none</em></span>
                            </div>
                            <strong>date:</strong> @{{ session.date }}<br/>
                            <strong>place:</strong> @{{ session.place }}<br/>
                            <strong>notes:</strong> @{{ session.notes }}<br/>
                        </div>
                    </div>
                    {{--Players--}}
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <h5>Players</h5>
                        </div>
                        <div class="col-sm-6 text-right">
                            @if ($user)
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                        data-target="#modal-player" @click="modalPlayerForCreate" v-if="!parseInt(session.concluded)">
                                    Add player
                                </button>

                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#modal-points" v-if="!parseInt(session.concluded)" @click="modalPoints">
                                    Edit points
                                </button>
                                <confirm-button button-text="Conclude" button-class="btn btn-sm btn-warning"
                                                button-icon="fa fa-lock" v-if="!parseInt(session.concluded)"
                                                @click="confirmCallback = function() { concludeSession() }; confirmText = 'Conclude session? This will lock players and calculate rankings.'">
                                </confirm-button>
                            @endif
                            <span v-if="parseInt(session.concluded)" class="text-info">
                                @if ($user)
                                <i class="fa fa-lock" aria-hidden="true" @click="unconcludeSession" style="cursor: pointer"></i>
                                @else
                                <i class="fa fa-lock" aria-hidden="true"></i>
                                @endif
                                Concluded
                            </span>
                        </div>
                    </div>
                    {{--Player table--}}
                    @include('list.players')
                    {{--Photos--}}
                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <h5>Photos</h5>
                        </div>
                        <div class="col-sm-6 text-right">
                            @if ($user)
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#modal-photo" @click="photoForm.game_session_id = session.id">
                                Add photo
                            </button>
                            @endif
                        </div>
                    </div>
                    {{--Gallery--}}
                    @include('other.gallery')
                </div>
            </div>

            {{--Ranking chart--}}
            @include('other.ranking-chart')

            {{--Sessions list--}}
            <div class="card mb-3">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="card-title page-header">
                                Game sessions<br/>
                                @include('other.season-string')
                            </h4>
                        </div>
                        <div class="col-sm-6 text-right">
                            @if ($user)
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#modal-session" @click="modalSessionForCreate">
                                Create session
                            </button>
                            @endif
                        </div>
                    </div>
                    {{--Session table--}}
                    @include('list.sessions')
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-3 pull-lg-9">
            <div class="row">
                <div class="col-12">
                    {{--Game info--}}
                    @include('other.game-info')
                </div>
                <div class="col-sm-6 col-lg-12">
                    {{--Seasons--}}
                    @include('other.seasons')
                </div>
                <div class="col-sm-6 col-lg-12">
                    {{--Leaderboard--}}
                    @include('other.ranking')
                </div>
                
                <div class="col-sm-6 col-lg-12">
                    {{--Factions--}}
                    @include('other.factions')
                </div>
                {{--Back to games list--}}
                <div class="col-sm-6 col-lg-12 text-center mt-3">
                    <a href="/" class="btn btn-primary mb-3">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        all games
                    </a>
                </div>
            </div>
        </div>

    {{--Session modal--}}
    @include('modal.sessions')

    {{--Player modal--}}
    @include('modal.players')

    {{--Photo modal--}}
    @include('modal.photos')

    {{--Edit points modal--}}
    @include('modal.points')

    {{--Season modal--}}
    @include('modal.seasons')

    {{--Faction modal--}}
    @include('modal.factions')

@stop

@section('script')
    @include('script.sessions')
@stop
