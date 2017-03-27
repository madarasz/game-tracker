@extends('layout.general')

@section('content')
    <div class="row mt-3" id="game-viewer">
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
                            <button type="button" class="btn btn-sm btn-danger" @click.prevent="deleteSession">
                                Delete
                            </button>
                            @endif
                            <button type="button" class="close ml-1" aria-label="Close" @click.prevent="session = {}">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
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

                                <button type="button" class="btn btn-sm btn-warning" @click="concludeSession"
                                    v-if="!parseInt(session.concluded)">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                    Conclude
                                </button>
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

            {{--Sessions list--}}
            <div class="card mb-3">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="card-title page-header">Game sessions</h4>
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
            {{--Ranking chart--}}
            @include('other.ranking-chart')
        </div>

        <div class="col-sm-12 col-lg-3 pull-lg-9">
            {{--Game info--}}
            @include('other.game-info')
            {{--Leaderboard--}}
            @include('other.ranking')

            {{--Back to games list--}}
            <div class="text-center mt-3">
                <a href="/" class="btn btn-primary mb-3">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    all games
                </a>
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

@stop

@section('script')
    @include('script.sessions')
@stop
