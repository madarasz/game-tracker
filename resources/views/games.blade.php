@extends('layout.general')

@section('content')
    <div class="card mt-3" id="manage-games">
        <div class="card-block">
            {{--Games Header--}}
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="card-title page-header">Games</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-game" @click="modalForCreate">
                        Create game
                    </button>
                </div>
            </div>

            {{--Game Listing--}}
            @include('list.games')
        </div>

        {{--Modal for new/edit game--}}
        @include('modal.games');
    </div>
@stop

@section('script')
    @include('script.games')
@stop
