@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        @include('ultimateLogo')

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
                        <div class="chat-room-head" id="header">
                            <div class="col-md-5 col-xs-12">
                                <h3>{{ !empty($chatRoomName) ? $chatRoomName->name . ' ' : '' }} {{ __('Room') }}</h3>
                            </div>
                            <!-- <form class="" method="__GET" action="{{ route('chat.group', $groupId) }}">
                                <div class="row">
                                    <div class="col-md-5 col-xs-12">
                                        <h3>{{ __('Lobby Room') }}</h3>
                                    </div>
                                    <div class="col-md-7 col-xs-12">
                                        <div class="form-inline float-md-right">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="text" placeholder="{{ __('Search by message') }}" class="form-control" name="s" value="{{ $request->get('s') }}">

                                                        @if($request->has('s') && $request->get('s') != "")
                                                            <a href="{{ route('chat.group', $groupId) }}" class="btn btn-light">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        @endif
                                                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>&nbsp;&nbsp;
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form> -->
                        </div>
                        <div class="chat-body">
                            <chat :room="{{ $chatRoomName }}" :chat-messages="{{ $chatMessages }}"></chat>
                        </div>
                    </aside>
                    <aside class="right-side">
                        <div class="user-head">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 id="header">{{ !empty($chatRoomName) ? $chatRoomName->name . ' ' : '' }}{{ __('Users') }}</h3>
                                </div>
                                <div class="col-md-4 pull-right text-right">
                                    <div class="create-lobby-user-model d-none">
                                        <form action="{{ route('chat.store') }}" method="POST">
                                            @csrf

                                            <create-chat-user :initial-users="{{ $chatUsers }}"></create-chat-user>

                                            <input type="hidden" name="is_create" value="user">
                                            <input type="hidden" name="chat_room_id" value="{{ $chatRoomName->id }}">
                                        </form>
                                    </div>
                                    <button class="btn btn-primary addChatUsers" title="{{ __('Add New User') }}" data-title="{{ __('Add New User') }}" data-html="create-lobby-user-model">{{ __('+ Add') }}</button>
                                    <a href="{{ route('chat.index') }}" class="btn btn-default"><i class="fa fa-arrow-left excluded"></i></a>
                                </div>
                            </div>
                        </div>
                        <ul class="chat-available-user">
                            @if (!empty($chatRoomUsers) && !$chatRoomUsers->isEmpty())
                                @foreach ($chatRoomUsers as $chatRoomUser)
                                    <li>
                                        <a href="{{ route('chat.individual', $chatRoomUser->user_id) }}">
                                            <img class="img-circle" src="{{ !empty($chatRoomUser->user->profile_photo) ? $chatRoomUser->user->profile_photo : asset('img/friends/fr-05.jpg') }}" width="32">
                                            {{ $chatRoomUser->user->fullName }}
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li>
                                    <a href="#">
                                        <img class="img-circle" src="{{ asset('img/friends/fr-05.jpg') }}" width="32">
                                        {{ __('No users') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
<script type="text/javascript">
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }
</script>
