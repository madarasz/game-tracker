@extends('layout.general')

@section('content')
    <div class="card mt-3" id="manage-games">
        <confirm-modal :modal-body="confirmText" :callback="confirmCallback"></confirm-modal>
        <passport-clients></passport-clients>
        <passport-authorized-clients></passport-authorized-clients>
        <passport-personal-access-tokens></passport-personal-access-tokens>
        <div class="card-block">
            {{--Games Header--}}
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="card-title page-header">Games</h4>
                </div>
                <div class="col-sm-6 text-right">
                    @if ($user)
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-game" @click="modalForCreate">
                        Create game
                    </button>
                    @endif
                </div>
            </div>

            {{--Game Listing--}}
            @include('list.games')
        </div>

        {{--Modal for new/edit game--}}
        @include('modal.games')
    </div>
@stop

@section('script')
    @include('script.games')
@stop
