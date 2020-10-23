@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Individual Chat') }}</h3>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="chat-room mt">
                    <aside class="mid-side">
                        <div class="chat-room-head">
                            <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <h3>{{ !empty($user) ? $user->fullname . ' ' : '' }}</h3>
                            </div>
                            <div class="col-md-7 col-xs-12 text-right">
                                <a href="{{ route('chat.index') }}" class="btn btn-default"><i class="fa fa-arrow-left excluded"></i></a>
                            </div>
                            </div>
                        </div>
                        <div class="chat-body">
                            <chat :users="{{ $user }}" :user-id="{{ $myUserId }}" :chat-messages="{{ $chatMessages }}"></chat>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
