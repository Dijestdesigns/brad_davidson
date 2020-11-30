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
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-panel">
                            <div class="panel panel-primary m10">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ __('Week 1') }}</h3>
                                    <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                </div>
                                <!-- <div>
                                    <div class="col-md-12">
                                        <label>
                                            <h2 id="header">{{ __('Week 1') }}</h2>
                                        </label>
                                    </div>
                                </div> -->
                                <div class="panel-body disp-none">
                                    <div class="table-responsive">
                                        <div class="col-md-12">
                                            <div class="tab">
                                                @for ($day = 1; $day <= 7; $day++)
                                                    <button class="tablinks" data-id="tasks" data-week="1" data-day="{{ $day }}">{{ __('Day') }} {{ $day }}</button>
                                                @endfor
                                            </div>
                                            @for ($day = 1; $day <= 7; $day++)
                                                <form method="POST" class="form-group" enctype="multipart/form-data" action="{{ route('coaching.client.store') }}">
                                                    @csrf

                                                    <div class="tasks-widget tasks-1-{{ $day }} disp-none">
                                                        <div class="panel-body">
                                                            <div class="task-content">
                                                                <ul class="task-list">
                                                                    @foreach ($coachings as $coaching)
                                                                        <li>
                                                                            <div class="task-checkbox">
                                                                                <input id="op{{ $coaching->id }}" name='coaching[{{ $coaching->id }}]' type='checkbox' value="{{ $coaching->id }}" {{ ($coaching->isDone($weekStartDate, false, $day)) ? 'checked="true"' : '' }} />
                                                                            </div>
                                                                            <div class="task-title">
                                                                                <span class="task-title-sp">{{ $coaching->name }}</span>
                                                                                @if ($coaching->isDone($weekStartDate, false, $day))
                                                                                    <span class="badge bg-theme">{{ __('Done') }}</span>
                                                                                @else
                                                                                    <span class="badge bg-important">{{ __('Pending') }}</span>
                                                                                @endif

                                                                                @if ($coaching->browse_file)
                                                                                    <div class="pull-right fs18">
                                                                                        <label for="browse-file-{{ $coaching->id }}-1-{{ $day }}" class="custom-file-upload">
                                                                                            <i class="fa fa-cloud-upload"></i> {{ __('Browse File') }}
                                                                                        </label>

                                                                                        <input type="file" class="form-control browse-file" id="browse-file-{{ $coaching->id }}-1-{{ $day }}" name="browse_file[{{ $coaching->id }}]" accept="image/*">
                                                                                    </div>
                                                                                @endif
                                                                                @if (!empty($coaching->clientcoaching($weekStartDate, false, $day)->first()->browse_file))
                                                                                    <div class="pull-right fs18">
                                                                                        <a href="{{ $coaching->clientCoaching($weekStartDate, false, $day)->first()->browse_file }}" target="__blank">
                                                                                            <label style="cursor: pointer;">
                                                                                                {{ __('View') }}&nbsp;&nbsp;
                                                                                            </label>
                                                                                        </a>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </li>
                                                                        <input type="hidden" name="wholeDayCoachings[{{ $coaching->id }}]" value="{{ $coaching->id }}">
                                                                        <input type="hidden" name="day[{{ $coaching->id }}]" value="{{ $day }}">
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" style="margin-top: -15px;margin-bottom: 15px;">
                                                            <input type="hidden" name="date" value="{{ $weekStartDate->format('Y-m-d') }}">
                                                            <input type="hidden" name="current_day" value="{{ $day }}">
                                                            <input type="submit" class="btn btn-success" value="{{ __('Submit') }}" id="submit" />
                                                        </div>
                                                    </div>
                                                </form>
                                                @php
                                                    $weekStartDate->addDays(1);
                                                @endphp
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary m10">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ __('Week 2') }}</h3>
                                    <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                </div>
                                <!-- <div>
                                    <div class="col-md-12">
                                        <label>
                                            <h2 id="header">{{ __('Week 2') }}</h2>
                                        </label>
                                    </div>
                                </div> -->
                                <div class="panel-body disp-none">
                                    <div class="table-responsive">
                                        <div class="col-md-12">
                                            <div class="tab">
                                                @for ($day = 1; $day <= 7; $day++)
                                                    <button class="tablinks" data-id="tasks" data-week="2" data-day="{{ $day }}">{{ __('Day') }} {{ $day }}</button>
                                                @endfor
                                            </div>
                                            @for ($day = 1; $day <= 7; $day++)
                                                <form method="POST" class="form-group" enctype="multipart/form-data" action="{{ route('coaching.client.store') }}">
                                                    @csrf

                                                    <div class="tasks-widget tasks-2-{{ $day }} disp-none">
                                                        <div class="panel-body">
                                                            <div class="task-content">
                                                                <ul class="task-list">
                                                                    @foreach ($coachings as $coaching)
                                                                        <li>
                                                                            <div class="task-checkbox">
                                                                                <input id="op{{ $coaching->id }}" name='coaching[{{ $coaching->id }}]' type='checkbox' value="{{ $coaching->id }}" {{ ($coaching->isDone($weekStartDate1, false, $day)) ? 'checked="true"' : '' }} />
                                                                            </div>
                                                                            <div class="task-title">
                                                                                <span class="task-title-sp">{{ $coaching->name }}</span>
                                                                                @if ($coaching->isDone($weekStartDate1, false, $day))
                                                                                    <span class="badge bg-theme">{{ __('Done') }}</span>
                                                                                @else
                                                                                    <span class="badge bg-important">{{ __('Pending') }}</span>
                                                                                @endif

                                                                                @if ($coaching->browse_file)
                                                                                    <div class="pull-right fs18">
                                                                                        <label for="browse-file-{{ $coaching->id }}-2-{{ $day }}" class="custom-file-upload">
                                                                                            <i class="fa fa-cloud-upload"></i> {{ __('Browse File') }}
                                                                                        </label>

                                                                                        <input type="file" class="form-control browse-file" id="browse-file-{{ $coaching->id }}-2-{{ $day }}" name="browse_file[{{ $coaching->id }}]" accept="image/*">
                                                                                    </div>
                                                                                @endif
                                                                                @if (!empty($coaching->clientcoaching($weekStartDate1, false, $day)->first()->browse_file))
                                                                                    <div class="pull-right fs18">
                                                                                        <a href="{{ $coaching->clientCoaching($weekStartDate1, false, $day)->first()->browse_file }}" target="__blank">
                                                                                            <label style="cursor: pointer;">
                                                                                                {{ __('View') }}&nbsp;&nbsp;
                                                                                            </label>
                                                                                        </a>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </li>
                                                                        <input type="hidden" name="wholeDayCoachings[{{ $coaching->id }}]" value="{{ $coaching->id }}">
                                                                        <input type="hidden" name="day[{{ $coaching->id }}]" value="{{ $day }}">
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" style="margin-top: -15px;margin-bottom: 15px;">
                                                            <input type="hidden" name="date" value="{{ $weekStartDate1->format('Y-m-d') }}">
                                                            <input type="hidden" name="current_day" value="{{ $day }}">
                                                            <input type="submit" class="btn btn-success" value="{{ __('Submit') }}" id="submit" />
                                                        </div>
                                                    </div>
                                                </form>
                                                @php
                                                    $weekStartDate1->addDays(1);
                                                @endphp
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary m10">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ __('Week 3') }}</h3>
                                    <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                </div>
                                <div class="panel-body disp-none">
                                    <div class="table-responsive">
                                        <div class="col-md-12">
                                            <div class="tab">
                                                @for ($day = 1; $day <= 7; $day++)
                                                    <button class="tablinks {{ $day == $currentWeekDay ? 'active': '' }}" data-id="tasks" data-week="3" data-day="{{ $day }}">{{ __('Day') }} {{ $day }}</button>
                                                @endfor
                                            </div>
                                            @for ($day = 1; $day <= 7; $day++)
                                                <form method="POST" class="form-group" enctype="multipart/form-data" action="{{ route('coaching.client.store') }}">
                                                    @csrf

                                                    <div class="tasks-widget tasks-3-{{ $day }} {{ $day == $currentWeekDay ? '' : 'disp-none' }}">
                                                        <div class="panel-body">
                                                            <div class="task-content">
                                                                <ul class="task-list">
                                                                    @foreach ($coachings as $coaching)
                                                                        <li>
                                                                            <div class="task-checkbox">
                                                                                <input id="op{{ $coaching->id }}" name='coaching[{{ $coaching->id }}]' type='checkbox' value="{{ $coaching->id }}" {{ ($coaching->isDone($weekStartDate2, false, $day)) ? 'checked="true"' : '' }} />
                                                                            </div>
                                                                            <div class="task-title">
                                                                                <span class="task-title-sp">{{ $coaching->name }}</span>
                                                                                @if ($coaching->isDone($weekStartDate2, false, $day))
                                                                                    <span class="badge bg-theme">{{ __('Done') }}</span>
                                                                                @else
                                                                                    <span class="badge bg-important">{{ __('Pending') }}</span>
                                                                                @endif

                                                                                @if ($coaching->browse_file)
                                                                                    <div class="pull-right fs18">
                                                                                        <label for="browse-file-{{ $coaching->id }}-3-{{ $day }}" class="custom-file-upload">
                                                                                            <i class="fa fa-cloud-upload"></i> {{ __('Browse File') }}
                                                                                        </label>

                                                                                        <input type="file" class="form-control browse-file" id="browse-file-{{ $coaching->id }}-3-{{ $day }}" name="browse_file[{{ $coaching->id }}]" accept="image/*">
                                                                                    </div>
                                                                                @endif
                                                                                @if (!empty($coaching->clientcoaching($weekStartDate2, false, $day)->first()->browse_file))
                                                                                    <div class="pull-right fs18">
                                                                                        <a href="{{ $coaching->clientCoaching($weekStartDate2, false, $day)->first()->browse_file }}" target="__blank">
                                                                                            <label style="cursor: pointer;">
                                                                                                {{ __('View') }}&nbsp;&nbsp;
                                                                                            </label>
                                                                                        </a>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </li>
                                                                        <input type="hidden" name="wholeDayCoachings[{{ $coaching->id }}]" value="{{ $coaching->id }}">
                                                                        <input type="hidden" name="day[{{ $coaching->id }}]" value="{{ $day }}">
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" style="margin-top: -15px;margin-bottom: 15px;">
                                                            <input type="hidden" name="date" value="{{ $weekStartDate2->format('Y-m-d') }}">
                                                            <input type="hidden" name="current_day" value="{{ $day }}">
                                                            <input type="submit" class="btn btn-success" value="{{ __('Submit') }}" id="submit" />
                                                        </div>
                                                    </div>
                                                </form>
                                                @php
                                                    $weekStartDate2->addDays(1);
                                                @endphp
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary m10">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ __('Week 4') }}</h3>
                                    <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                </div>
                                <div class="panel-body disp-none">
                                    <div class="table-responsive">
                                        <div class="col-md-12">
                                            <div class="tab">
                                                @for ($day = 1; $day <= 7; $day++)
                                                    <button class="tablinks {{ $day == $currentWeekDay ? 'active': '' }}" data-id="tasks" data-week="4" data-day="{{ $day }}">{{ __('Day') }} {{ $day }}</button>
                                                @endfor
                                            </div>
                                            @for ($day = 1; $day <= 7; $day++)
                                                <form method="POST" class="form-group" enctype="multipart/form-data" action="{{ route('coaching.client.store') }}">
                                                    @csrf

                                                    <div class="tasks-widget tasks-4-{{ $day }} {{ $day == $currentWeekDay ? '' : 'disp-none' }}">
                                                        <div class="panel-body">
                                                            <div class="task-content">
                                                                <ul class="task-list">
                                                                    @foreach ($coachings as $coaching)
                                                                        <li>
                                                                            <div class="task-checkbox">
                                                                                <input id="op{{ $coaching->id }}" name='coaching[{{ $coaching->id }}]' type='checkbox' value="{{ $coaching->id }}" {{ ($coaching->isDone($weekStartDate3, false, $day)) ? 'checked="true"' : '' }} />
                                                                            </div>
                                                                            <div class="task-title">
                                                                                <span class="task-title-sp">{{ $coaching->name }}</span>
                                                                                @if ($coaching->isDone($weekStartDate3, false, $day))
                                                                                    <span class="badge bg-theme">{{ __('Done') }}</span>
                                                                                @else
                                                                                    <span class="badge bg-important">{{ __('Pending') }}</span>
                                                                                @endif

                                                                                @if ($coaching->browse_file)
                                                                                    <div class="pull-right fs18">
                                                                                        <label for="browse-file-{{ $coaching->id }}-4-{{ $day }}" class="custom-file-upload">
                                                                                            <i class="fa fa-cloud-upload"></i> {{ __('Browse File') }}
                                                                                        </label>

                                                                                        <input type="file" class="form-control browse-file" id="browse-file-{{ $coaching->id }}-4-{{ $day }}" name="browse_file[{{ $coaching->id }}]" accept="image/*">
                                                                                    </div>
                                                                                @endif
                                                                                @if (!empty($coaching->clientcoaching($weekStartDate3, false, $day)->first()->browse_file))
                                                                                    <div class="pull-right fs18">
                                                                                        <a href="{{ $coaching->clientCoaching($weekStartDate3, false, $day)->first()->browse_file }}" target="__blank">
                                                                                            <label style="cursor: pointer;">
                                                                                                {{ __('View') }}&nbsp;&nbsp;
                                                                                            </label>
                                                                                        </a>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </li>
                                                                        <input type="hidden" name="wholeDayCoachings[{{ $coaching->id }}]" value="{{ $coaching->id }}">
                                                                        <input type="hidden" name="day[{{ $coaching->id }}]" value="{{ $day }}">
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" style="margin-top: -15px;margin-bottom: 15px;">
                                                            <input type="hidden" name="date" value="{{ $weekStartDate3->format('Y-m-d') }}">
                                                            <input type="hidden" name="current_day" value="{{ $day }}">
                                                            <input type="submit" class="btn btn-success" value="{{ __('Submit') }}" id="submit" />
                                                        </div>
                                                    </div>
                                                </form>
                                                @php
                                                    $weekStartDate3->addDays(1);
                                                @endphp
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary m10">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ __('Week 5') }}</h3>
                                    <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div class="col-md-12">
                                            <div class="tab">
                                                @for ($day = 1; $day <= 7; $day++)
                                                    <button class="tablinks {{ $day == $currentWeekDay ? 'active': '' }}" data-id="tasks" data-week="5" data-day="{{ $day }}">{{ __('Day') }} {{ $day }}</button>
                                                @endfor
                                            </div>
                                            @for ($day = 1; $day <= 7; $day++)
                                                <form method="POST" class="form-group" enctype="multipart/form-data" action="{{ route('coaching.client.store') }}">
                                                    @csrf

                                                    <div class="tasks-widget tasks-5-{{ $day }} {{ $day == $currentWeekDay ? '' : 'disp-none' }}">
                                                        <div class="panel-body">
                                                            <div class="task-content">
                                                                <ul class="task-list">
                                                                    @foreach ($coachings as $coaching)
                                                                        <li>
                                                                            <div class="task-checkbox">
                                                                                <input id="op{{ $coaching->id }}" name='coaching[{{ $coaching->id }}]' type='checkbox' value="{{ $coaching->id }}" {{ ($coaching->isDone($weekStartDate4, false, $day)) ? 'checked="true"' : '' }} />
                                                                            </div>
                                                                            <div class="task-title">
                                                                                <span class="task-title-sp">{{ $coaching->name }}</span>
                                                                                @if ($coaching->isDone($weekStartDate4, false, $day))
                                                                                    <span class="badge bg-theme">{{ __('Done') }}</span>
                                                                                @else
                                                                                    <span class="badge bg-important">{{ __('Pending') }}</span>
                                                                                @endif

                                                                                @if ($coaching->browse_file)
                                                                                    <div class="pull-right fs18">
                                                                                        <label for="browse-file-{{ $coaching->id }}-5-{{ $day }}" class="custom-file-upload">
                                                                                            <i class="fa fa-cloud-upload"></i> {{ __('Browse File') }}
                                                                                        </label>

                                                                                        <input type="file" class="form-control browse-file" id="browse-file-{{ $coaching->id }}-5-{{ $day }}" name="browse_file[{{ $coaching->id }}]" accept="image/*">
                                                                                    </div>
                                                                                @endif
                                                                                @if (!empty($coaching->clientcoaching($weekStartDate4, false, $day)->first()->browse_file))
                                                                                    <div class="pull-right fs18">
                                                                                        <a href="{{ $coaching->clientCoaching($weekStartDate4, false, $day)->first()->browse_file }}" target="__blank">
                                                                                            <label style="cursor: pointer;">
                                                                                                {{ __('View') }}&nbsp;&nbsp;
                                                                                            </label>
                                                                                        </a>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </li>
                                                                        <input type="hidden" name="wholeDayCoachings[{{ $coaching->id }}]" value="{{ $coaching->id }}">
                                                                        <input type="hidden" name="day[{{ $coaching->id }}]" value="{{ $day }}">
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" style="margin-top: -15px;margin-bottom: 15px;">
                                                            <input type="hidden" name="date" value="{{ $weekStartDate4->format('Y-m-d') }}">
                                                            <input type="hidden" name="current_day" value="{{ $day }}">
                                                            <input type="submit" class="btn btn-success" value="{{ __('Submit') }}" id="submit" />
                                                        </div>
                                                    </div>
                                                </form>
                                                @php
                                                    $weekStartDate4->addDays(1);
                                                @endphp
                                            @endfor
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
