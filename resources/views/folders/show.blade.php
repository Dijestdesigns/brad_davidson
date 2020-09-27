@extends('layouts.app')

@section('content')

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Client Details') }}</h3>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            @if (is_array(session('error')))
                <div class="alert alert-danger" role="alert">
                    @foreach (session('error') as $error)
                        {{ $error }}<br />
                    @endforeach
                </div>
            @else
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    <div class="p-10">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Name') }}</div>
                                        <div class="col-md-8">
                                            {{ $client->name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Surname') }}</div>
                                        <div class="col-md-8">
                                            {{ $client->surname }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Email') }}</div>
                                        <div class="col-md-8">
                                            {{ $client->email }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Tags') }}</div>
                                        <div class="col-md-8">
                                            @php
                                                $tagNames = [];

                                                if (!empty($client->clientTags) && !$client->clientTags->isEmpty()) {
                                                    foreach ($client->clientTags as $clientTag) {
                                                        $tagNames[] = $clientTag->tag->name;
                                                    }
                                                }
                                            @endphp

                                            {{ implode(", ", $tagNames) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Category') }}</div>
                                        <div class="col-md-8">
                                            {{ (!empty(App\User::$categories[$client->category])) ? App\User::$categories[$client->category] : __('None') }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Notes') }}</div>
                                        <div class="col-md-8">
                                            {{ $client->notes }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Created at') }}</div>
                                        <div class="col-md-3">
                                            {{$client->created_at}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Role') }}</div>
                                        <div class="col-md-3">
                                            <span class="badge badge-lg badge-secondary text-white">{{@$client->getRoleNames()[0]}}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2">{{ __('Permissions') }}</div>
                                        <div class="col-md-10">
                                            <table class="table table-striped table-bordered permissions_table">
                                                @foreach($groups as $group)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-2 font-weight-bold">{{$group['name']}}</h6>
                                                            <div>
                                                                @foreach($group['permissions'] as $perm)
                                                                    <label class="mr-4">
                                                                        @if($client->hasPermissionTo($perm['id'])) 
                                                                            <i class="fa fa-plus" style="color: green;"></i>
                                                                        @else
                                                                            <i class="fa fa-minus" style="color: red;"></i>
                                                                        @endif
                                                                        {{$perm['display_name'] !== null ? $perm['display_name'] : $perm['name']}}
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
