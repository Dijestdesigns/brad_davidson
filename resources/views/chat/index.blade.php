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
                    @can('chat_group_access')
                        <aside class="mid-side">
                            <div class="chat-room-head">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12" id="header">
                                        <h3>{{ __('Ultimate Comeback Challenge') }}</h3>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="float-md-right">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="@can('chat_group_create') col-md-10 @else col-md-12 @endcan col-xs-12">
                                                        <form class="form-inline" method="__GET" action="{{ route('chat.index') }}">
                                                            <input type="text" placeholder="{{ __('Search by room name') }}" class="form-control" name="s" value="{{ $request->get('s') }}">
                                                            @if($request->has('s') && $request->get('s') != "")
                                                                <a href="{{ route('chat.index') }}" class="btn btn-light">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            @endif
                                                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-2 col-xs-12">
                                                        <div class="create-lobby-model d-none">
                                                            <form action="{{ route('chat.store') }}" method="POST">
                                                                @csrf

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label class="col-md-12 col-xs-12">{{ __('Lobby Name') }}</label>

                                                                        <div class="col-md-12 col-xs-12">
                                                                            <input type="text"  name="name" class="form-control" placeholder="{{ __('Enter lobby name') }}" />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <br />
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label class="col-md-12 col-xs-12">{{ __('Lobby Descriptions') }}</label>

                                                                        <div class="col-md-12 col-xs-12">
                                                                            <textarea class="form-control" placeholder="{{ __('Enter lobby descriptions') }}" name="descriptions"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <br />
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="col-md-12 col-xs-12">
                                                                            <button class="btn btn-primary"><i class="fa fa-save"></i></button>
                                                                            <button type="button" class="btn btn-secondary btn-default bootbox-cancel close-model" style="float: none;"><i class="fa fa-close"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="is_create" value="lobby">
                                                            </form>
                                                        </div>
                                                        @can('chat_group_create')
                                                            <form action="{{ route('chat.store') }}" method="POST">
                                                                @csrf

                                                                <button class="btn btn-primary pull-right addChatGroups" title="{{ __('Create New Lobby') }}" data-title="{{ __('Create New Lobby') }}" data-html="create-lobby-model"><i class="fa fa-plus"></i></button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="room-desk">
                                @if (!empty($chatRooms) && !$chatRooms->isEmpty())
                                    @foreach ($chatRooms as $chatRoom)
                                        <div class="room-box">
                                            <h5 class="text-primary">
                                                <a href="{{ route('chat.group', $chatRoom->id) }}">{{ $chatRoom->name }}</a>
                                            </h5>
                                            <p>{{ $chatRoom->descriptions }}</p>
                                            <p>
                                                <span class="text-muted">{{ __('Created At') }} :</span> {{ $chatRoom->created_at }} | 
                                                <span class="text-muted">{{ __('Members') }} :</span> {{ $chatRoom->getTotalUsers() }} | 
                                                <span class="text-muted">{{ __('Last Activity') }} :</span> {{ $chatRoom->getTimeAgo('-') }}
                                            </p>
                                            <a class="btn btn-default btn-xs btn-round" href="{{ route('chat.group', $chatRoom->id) }}" data-toggle="tooltip" data-placement="top" title="{{__('New Messages')}}">{{ $chatRoom->getUnread() }}</a>
                                            <div class="form-inline pull-right">
                                                <a class="btn btn-primary" href="{{ route('chat.group', $chatRoom->id) }}" data-toggle="tooltip" data-placement="top" title="{{__('Start Chat')}}"><i class="fa fa-comments-o"></i></a>
                                                <a class="btn btn-info" href="{{ route('chat.index', ['i' => $chatRoom->id, 's' => $request->get('s', '')]) }}" data-toggle="tooltip" data-placement="top" title="{{__('Check Users')}}"><i class="fa fa-info"></i></a>

                                                @can('chat_group_edit')
                                                    <div class="edit-lobby-model-{{ $chatRoom->id }} d-none">
                                                        <form action="{{ route('chat.update', $chatRoom->id) }}" method="POST">
                                                            @method('PATCH')
                                                            @csrf

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="col-md-12 col-xs-12">{{ __('Lobby Name') }}</label>

                                                                    <div class="col-md-12 col-xs-12">
                                                                        <input type="text"  name="name" class="form-control" placeholder="{{ __('Enter lobby name') }}" value="{{ $chatRoom->name }}" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <br />
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="col-md-12 col-xs-12">{{ __('Lobby Descriptions') }}</label>

                                                                    <div class="col-md-12 col-xs-12">
                                                                        <textarea class="form-control" placeholder="{{ __('Enter lobby descriptions') }}" name="descriptions">{{ $chatRoom->descriptions }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <br />
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12 col-xs-12">
                                                                        <button class="btn btn-primary"><i class="fa fa-save"></i></button>
                                                                        <button type="button" class="btn btn-secondary btn-default bootbox-cancel close-model" style="float: none;"><i class="fa fa-close"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="is_create" value="lobby">
                                                        </form>
                                                    </div>
                                                    <button class="btn btn-default editChatGroups" title="{{ __('Edit Lobby') }}" data-title="{{ __('Edit Lobby') }}" data-html="edit-lobby-model-{{ $chatRoom->id }}" title="{{ __('Edit') }}"><i class="fa fa-edit"></i></button>&nbsp;&nbsp;
                                                @endcan

                                                @can('chat_group_delete')
                                                    <form action="{{ route('chat.destroy', $chatRoom->id) }}" method="POST" style="display: inline-block;margin-left: -8px;">
                                                        @method('DELETE')
                                                        @csrf

                                                        <a href="#" class="deleteBtn btn btn-danger btn-sm" data-confirm-message="{{__("Are you sure you want to delete this?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete')}}"><i class="fa fa-trash"></i></a>

                                                        <input type="hidden" name="is_delete" value="lobby">
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="room-box">
                                        <h5 class="text-primary"><a href="#">{{ __('No lobby found') }}</a></h5>
                                    </div>
                                @endif
                            </div>
                        </aside>
                    @endcan
                    @can('chat_access')
                        <aside class="right-side">
                            @if ($request->has('i') && !empty($request->get('i')))
                                <div class="user-head">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h3>{{ !empty($chatRoomName) ? $chatRoomName->name . ' ' : '' }}{{ __('Users') }}</h3>
                                        </div>
                                        <div class="col-md-4 pull-right">
                                            <a href="{{ route('chat.index', ['s' => $request->get('s', '')]) }}" class="btn btn-danger btn-xs pull-right"><i class="fa fa-close excluded"></i></a>
                                            @can('chat_group_add_user')
                                                <div class="create-lobby-user-model d-none">
                                                    <form action="{{ route('chat.store') }}" method="POST">
                                                        @csrf

                                                        <create-chat-user :initial-users="{{ $chatUsers }}"></create-chat-user>

                                                        <input type="hidden" name="is_create" value="user">
                                                        <input type="hidden" name="chat_room_id" value="{{ !empty($chatRoomName->id) ? $chatRoomName->id : '' }}">
                                                    </form>
                                                </div>
                                                <button class="btn btn-primary addChatUsers pull-right" title="{{ __('Add New User') }}" data-title="{{ __('Add New User') }}" data-html="create-lobby-user-model" style="margin-right: 5px;">{{ __('+ Add') }}</button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                                <ul class="chat-available-user">
                                    @if (!empty($chatRoomUsers) && !$chatRoomUsers->isEmpty())
                                        @foreach ($chatRoomUsers as $chatRoomUser)
                                            <li>
                                                <a href="{{ route('chat.individual', $chatRoomUser->user_id) }}">
                                                    <img class="img-circle" src="{{ $chatRoomUser->user->profile_photo }}" width="32">
                                                    {{ $chatRoomUser->user->fullName }}
                                                </a>
                                                @can('chat_group_delete_user')
                                                    @if (!$chatRoomUser->user->isSuperAdmin())
                                                        <form action="{{ route('chat.room.destroyUser', $chatRoomUser->id) }}" method="POST" class="pull-right">
                                                            @method('DELETE')
                                                            @csrf
                                                            <a class="btn btn-default btn-xs btn-round deleteBtn" href="#" data-toggle="tooltip" data-placement="top" title="{{__('Delete User. Make sure we won\'t recover this user chat in future.')}}" data-confirm-message="{{__('Delete User')}}"><i class="fa fa-trash"></i></a>

                                                            <input type="hidden" name="s" value="{{ $request->get('s', '') }}">
                                                            <input type="hidden" name="i" value="{{ $request->get('i', '') }}">
                                                        </form>
                                                    @endif
                                                @endcan
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
                            @else
                                <div class="user-head">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 id="header">{{ __('Your Coach') }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <ul class="chat-available-user">
                                    @if (!empty($users) && !$users->isEmpty())
                                        @foreach ($users as $user)
                                            <li>
                                                <a href="{{ route('chat.individual', $user->id) }}">
                                                    <img class="img-circle" src="{{ $user->profile_photo }}" width="32">
                                                    {{ $user->fullName }}
                                                </a>
                                                <a class="btn btn-default btn-xs btn-round pull-right" href="{{ route('chat.individual', $user->id) }}" data-toggle="tooltip" data-placement="top" title="{{__('New Messages')}}">{{ $user->getUnread() }}</a>
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
                            @endif
                        </aside>
                    @endcan
                </div>
            </div>
        </div>
    </section>
@endsection
