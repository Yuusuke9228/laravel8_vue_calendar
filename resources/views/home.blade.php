@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">My Scheduler</div>
                    <div class="card-body">
                        <!--ここにカレンダーを表示します。-->

                        <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}" />

                        <div id="app">
                            <home-component></home-component>
                        </div>
                        <!--ここまで-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
