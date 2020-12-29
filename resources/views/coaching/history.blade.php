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
                <h4>{{ __('Search Form : ') }}</h4>
                <div class="form-inline search-form">
                    <form method="__GET" action="{{ route('coaching.client.history', $userId) }}">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control datepicker" placeholder="{{ __('Start Date') }}" value="{{ $request->get('s', '') }}" autocomplete="off" />
                            <input type="text" name="f" class="form-control datepicker" placeholder="{{ __('Finish Date') }}" value="{{ $request->get('f', '') }}" autocomplete="off" />

                            @if($isFiltered == true || $request->get('t') == "0")
                                <a href="{{route('coaching.client.history', $userId)}}" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-panel">
                            <!-- <div class="row">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Coachings') }}</h4>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="float-right text-muted">
                                        {{__('Showing')}} {{ $records->firstItem() }} - {{ $records->lastItem() }} / {{ $records->total() }} ({{__('page')}} {{ $records->currentPage() }} )&nbsp;
                                    </h5>
                                </div>
                            </div> -->

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label>&nbsp;</label>
                                    <span class="badge" style="background-color: #337ab7 !important;">&nbsp;</span>
                                    {{ __('Running.') }}
                                    <label>&nbsp;</label>
                                    <span class="badge" style="background-color: #dff0d8 !important;">&nbsp;</span>
                                    {{ __('Client attended.') }}
                                    <label>&nbsp;</label>
                                    <span class="badge" style="background-color: #f2dede !important;">&nbsp;</span>
                                    {{ __('Client doesn\'t attended and outdated.') }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 1') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate1->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate1->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 2') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate2, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate3->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate2, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate2, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate3->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate2->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 3') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate5, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate4->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate5, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate5, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate4->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate5->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 4') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate7, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate6->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate7, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate7, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate6->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate7->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 5') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate8, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate9->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate8, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate8, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate9->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate8->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 6') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate11, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate10->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate11, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate11, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate10->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate11->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 7') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate13, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate12->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate13, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate13, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate12->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate13->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 8') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate14, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate15->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate14, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate14, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate15->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate14->addDays(1);
                                @endphp
                            @endfor

                            <div class="col-md-12">
                                <label>
                                    <h2 id="header">{{ __('Week 9') }}</h2>
                                </label>
                            </div>

                            @for ($day = 1; $day <= 7; $day++)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary m10">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Day {{ $day }}</h3>
                                                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                            </div>
                                            <div class="panel-body disp-none">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Coaching Name') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Attended') }}</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if (!empty($coachings) && !$coachings->isEmpty())
                                                                @foreach ($coachings as $index => $coaching)
                                                                    @php
                                                                        $clientCoaching = $coaching->clientCoaching($weekStartDate16, $userId)->first();
                                                                    @endphp

                                                                    <tr>
                                                                        <td>
                                                                            {{ $weekStartDate17->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $coaching->name }}
                                                                        </td>
                                                                        <td>
                                                                            @if (!empty($clientCoaching->browse_file))
                                                                                <a href="{{ $clientCoaching->browse_file }}" target="__blank">
                                                                                    <img src="{{ $clientCoaching->browse_file }}" height="50" width="50" />
                                                                                </a>
                                                                            @else
                                                                                {{ __('-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $coaching->isDone($weekStartDate16, $userId) ? 'bg-info' : 'bg-important' }}">
                                                                                <i class="fa {{ $coaching->isDone($weekStartDate16, $userId) ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $weekStartDate17->addDays(1);
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label>
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $weekStartDate16->addDays(1);
                                @endphp
                            @endfor

                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="float-left ml-10">
                                        @if(!empty($request))
                                            {{ $records->appends($request->all())->links() }}
                                        @else
                                            {{ $records->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
