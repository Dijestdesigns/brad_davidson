@extends('layouts.app')

@section('content')
    <section class="wrapper">
        @include('ultimateLogo')
    </section>

    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 id="header">{{ __('Welcome to your ultimate comeback challenge') }}</h1>
            </div>
        </div>
    </section>

    <section class="wrapper">
        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Dashboard') }}</h3>
                </div>
            </div>
        </div> -->

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

        @if ($currentUserRole != 'Normal Clients')
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <div class="gray-panel pn">
                                <div class="gray-header">
                                    <h5><p>{{ __('Total Items') }}</p></h5>
                                </div>
                                <p class="user"><i class="fa fa-object-group"></i>&nbsp; {{ $itemCount }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="yellow-panel pn">
                                <div class="yellow-header">
                                    <h5><p>{{ __('Total Clients') }}</p></h5>
                                </div>
                                <p class="user"><i class="fa fa-users"></i>&nbsp;{{ $userCount }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="green-panel pn">
                                <div class="green-header">
                                    <h5><p>{{ __('Total Stock') }}</p></h5>
                                </div>
                                <p class="user"><i class="fa fa-database"></i>&nbsp;{{ $totalStocks }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="red-panel pn">
                                <div class="red-header">
                                    <h5><p>{{ __('Total Values') }}</p></h5>
                                </div>
                                <p class="user"><i class="fa fa-money"></i>&nbsp;${{ $totalValues }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- /row -->
                </div>
                <!-- /col-lg-3 -->
            </div>
        @endif
        <!-- /row -->

        @if ($currentUserRole == 'Normal Clients')
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 mb"></div>
                        @can('chat_access')
                            <div class="col-lg-2 col-md-2 col-sm-2 mb">
                                <div class="product-panel-2 h250">
                                    <img src="{{ asset('img/icons/Message.I01.2k.png') }}" width="180" height="180" alt="">
                                    <div>&nbsp;</div>
                                    <a class="btn btn-small btn-theme04" href="{{ route('chat.index') }}">
                                        {{ __('Chat Now') }}
                                    </a>
                                </div>
                            </div>
                        @endcan

                        @can('calendar_access')
                            <div class="col-lg-2 col-md-2 col-sm-2 mb">
                                <div class="product-panel-2 h250">
                                    <img src="{{ asset('img/icons/Card_calendar.png') }}" width="180" height="180" alt="">
                                    <div>&nbsp;</div>
                                    <a class="btn btn-small btn-theme04" href="{{ route('calendar.index') }}">
                                        {{ __('Calendar') }}
                                    </a>
                                </div>
                            </div>
                        @endcan

                        @can('training_show_to_clients')
                            <div class="col-lg-2 col-md-2 col-sm-2 mb">
                                <div class="product-panel-2 h250">
                                    <img src="{{ asset('img/icons/Dumbbells_blue.png') }}" width="180" height="180" alt="">
                                    <div>&nbsp;</div>
                                    <a class="btn btn-small btn-theme04" href="{{ route('training.client.index') }}">
                                        {{ __('Training') }}
                                    </a>
                                </div>
                            </div>
                        @endcan

                        @can('note_access')
                            <div class="col-lg-2 col-md-2 col-sm-2 mb">
                                <div class="product-panel-2 h250">
                                    <img src="{{ asset('img/icons/Sticky_Notes_Green_.png') }}" width="180" height="180" alt="">
                                    <div>&nbsp;</div>
                                    <a class="btn btn-small btn-theme04" href="{{ route('notes.index') }}">
                                        {{ __('Notes') }}
                                    </a>
                                </div>
                            </div>
                        @endcan
                        <div class="col-lg-1 col-md-1 col-sm-1 mb"></div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <section class="wrapper">

        @can('supplements_access')
            @if (!auth()->user()->isSuperAdmin())
                <div class="row">
                    <div class="col-lg-12">
                        <div class="border-head">
                            <h3><i class="fa fa-angle-right"></i> {{ __('Latest supplement') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 {{ ((!empty($logs) && !$logs->isEmpty())) ? 'ds' : '' }}">
                        @if (!empty($supplements))
                            <div class="desc">
                                <div class="text-center">
                                    <h4>
                                        {{ __('Supplement Date') }} {{ date('Y-m-d', strtotime($supplements->date)) }}
                                    </h4>
                                </div>
                                <div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered full-inputs">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">{{ __('SUPPLEMENT') }}</th>
                                                    <th class="text-center">{{ __('UPON WAKING') }}</th>
                                                    <th class="text-center">{{ __('AT BREAKFAST') }}</th>
                                                    <th class="text-center">{{ __('AT LUNCH') }}</th>
                                                    <th class="text-center">{{ __('AT DINNER') }}</th>
                                                    <th class="text-center">{{ __('BEFORE BED') }}</th>
                                                </tr>
                                                </thead>
                                            <tbody>
                                                @php
                                                    $supplementDatas = $supplements::where('user_id', $supplements->user_id)->whereDate("date", $supplements->date)->get();

                                                    if (!empty($supplementDatas) && !$supplementDatas->isEmpty()) {
                                                        $supplementDatas = $supplementDatas->keyBy('row_id');
                                                    }
                                                @endphp

                                                @for($rowId = 1; $rowId <= $supplements::TOTAL_ROWS; $rowId++)
                                                    <tr>
                                                        <td>
                                                            <textarea class="form-control" rows="5" disabled="">{{ !empty($supplementDatas[$rowId]->supplement) ? $supplementDatas[$rowId]->supplement : '' }}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" rows="5" disabled="">{{ !empty($supplementDatas[$rowId]->upon_waking) ? $supplementDatas[$rowId]->upon_waking : '' }}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" rows="5" disabled="">{{ !empty($supplementDatas[$rowId]->at_breakfast) ? $supplementDatas[$rowId]->at_breakfast : '' }}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" rows="5" disabled="">{{ !empty($supplementDatas[$rowId]->at_lunch) ? $supplementDatas[$rowId]->at_lunch : '' }}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" rows="5" disabled="">{{ !empty($supplementDatas[$rowId]->at_dinner) ? $supplementDatas[$rowId]->at_dinner : '' }}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" rows="5" disabled="">{{ !empty($supplementDatas[$rowId]->before_bed) ? $supplementDatas[$rowId]->before_bed : '' }}</textarea>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="desc text-center">
                                <div class="details">
                                    <p>
                                        <mark>{{ __('No record found!') }}</mark>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endcan

        @can('logs_access')
            <div class="row">
                <div class="col-lg-12">
                    <div class="border-head">
                        <h3><i class="fa fa-angle-right"></i> {{ __('Recent activity (Top 10)') }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 {{ ((!empty($logs) && !$logs->isEmpty())) ? 'ds' : '' }}">
                    @if (!empty($logs) && !$logs->isEmpty())
                        @foreach ($logs as $log)
                            @if (empty($log->userCreatedBy))
                                @continue
                            @endif

                            <div class="desc">
                                <div class="thumb">
                                    <a href="{{ route('logs.index', ['hash' => $log->id]) }}" target="__blank">
                                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                    </a>
                                </div>
                                <div class="details">
                                    <p>
                                        <a href="#">{{ $log->userCreatedBy->name }}</a> {{ $log->message }} - {{ $log->created_at }} ({{ $log->ip_address }})<br>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="desc text-center">
                            <div class="details">
                                <p>
                                    <mark>{{ __('No record found!') }}</mark>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endcan

        @if (false && !auth()->user()->isSuperAdmin() && auth()->user()->can('training_show_to_clients') && !empty($trainings) && !$trainings->isEmpty())
            @section('styles')
                <style type="text/css">
                    @foreach ($trainings as $training)
                        #op{{ $training->id }}:checked ~ label[for=op{{ $training->id }}]:before {
                            border: 2px solid #96c93c;
                            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAHCAYAAAA1WQxeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAGFJREFUeNpinHLMhgEHKADia0xQThIQs6JJ9gPxZhYQAcS6QHwDiI8hSYJAC0gBPxDLAvFcIJ6JJJkDxFNBVtgBcQ8Qa6BLghgwN4A4a9ElQYAFSj8C4mwg3o8sCQIAAQYA78QTYqnPZuEAAAAASUVORK5CYII=') no-repeat center center;
                        }
                    @endforeach
                    .steps label.excluded:before {
                        display: none;
                    }
                    .steps label.excluded {
                        text-indent: 2px;
                    }
                </style>
            @endsection

            <div class="row">
                <div class="col-lg-12">
                    <div class="border-head">
                        <h3><i class="fa fa-angle-right"></i> {{ __('Training') }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb">
                    <div class="steps pn">
                        <form method="POST" class="form-group" enctype="multipart/form-data" action="{{ route('training.client.store') }}">
                            @csrf

                            @if (!empty($trainings[0]->day))
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="text-center excluded">
                                            <h2 style="padding-top: 15px;">
                                                {{ __('Day') }} {{ $trainings[0]->day }}
                                            </h2>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            @php
                                $isAttended    = false;
                                $isAllAttended = true;
                            @endphp

                            @foreach ($trainings as $training)
                                @php
                                    if (!$isAttended) {
                                        $isAttended = ($training->is_attended == App\ClientTraining::IS_ATTENDED);
                                    }

                                    if ($training->is_attended == App\ClientTraining::IS_NOT_ATTENDED) {
                                        $isAllAttended = false;
                                    }
                                @endphp
                                <div class="row row-no-padding">
                                    <div class="col-md-{{ $training->training->browse_file ? !empty($training->browse_file) ? '8 right' : '10 right' : '12' }} col-xs-12">
                                        <input id="op{{ $training->id }}" name='training[{{ $training->id }}]' type='checkbox' value="{{ $training->id }}" {{ ($training->is_attended == App\ClientTraining::IS_ATTENDED) ? 'checked="true"' : '' }} />

                                        <label for="op{{ $training->id }}">
                                            {{ $training->training->name }}
                                        </label>
                                    </div>
                                    @if (!empty($training->browse_file))
                                        <div class="col-md-2 col-xs-12 left right">
                                            <a href="{{ $training->browse_file }}" target="__blank">
                                                <label class="excluded text-center">
                                                    {{ __('View') }}
                                                </label>
                                            </a>
                                        </div>
                                    @endif
                                    @if ($training->training->browse_file)
                                        <div class="col-md-2 col-xs-12 left">
                                            <label for="browse-file" class="custom-file-upload excluded">
                                                <i class="fa fa-cloud-upload"></i> {{ __('Browse File') }}
                                            </label>

                                            <input type="file" class="form-control browse-file" id="browse-file" name="browse_file[{{ $training->id }}]">
                                        </div>
                                    @endif

                                    <input type="hidden" name="wholeDayTrainings[{{ $training->id }}]" value="{{ $training->id }}">
                                    <input type="hidden" name="client_training_info_id" value="{{ $training->client_training_info_id }}">
                                    <input type="hidden" name="current_day" value="{{ !empty($trainings[0]->day) ? $trainings[0]->day : 0 }}">
                                </div>
                            @endforeach

                            <input type="submit" value="{{ __('Submit') }}" id="submit" />
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </section>

    @if (isset($isAttended) && !$isAttended)
        @push('custom_scripts')
            window.onload = function() {
                setTimeout(function() {
                    bootbox.alert({
                        size: "medium",
                        title: "Training",
                        message: "You didn't attended any of one trainings for today!",
                        closeButton: false
                    });
                }, 500);
            };
        @endpush
    @elseif (!empty($trainings[0]) && !empty($trainings[0]->trainingInfo) && $trainings[0]->trainingInfo->is_done == App\ClientTrainingInfo::IS_DONE && $isAllAttended)
        @push('custom_scripts')
            window.onload = function() {
                setTimeout(function() {
                    bootbox.alert({
                        size: "medium",
                        title: "Training Done!",
                        message: "Today is last day of your training and you completed all trainings!",
                        closeButton: false
                    });
                }, 500);
            };
        @endpush
    @endif
@endsection
