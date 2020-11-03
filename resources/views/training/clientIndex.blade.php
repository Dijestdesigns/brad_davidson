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
                            <div>
                                <div class="col-md-12">
                                    <label>
                                        <h2 id="header">{{ __('Week 1') }}</h2>
                                    </label>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="col-md-12">
                                    <div class="tab">
                                        @for ($day = 1; $day <= 7; $day++)
                                            <button class="tablinks {{ $day == $currentWeekDay ? 'active': '' }}" data-id="tasks" data-week="1" data-day="{{ $day }}">{{ __('Day') }} {{ $day }}</button>
                                        @endfor
                                    </div>
                                    @for ($day = 1; $day <= 7; $day++)
                                        <form method="POST" class="form-group" enctype="multipart/form-data" action="{{ route('training.client.store') }}">
                                            @csrf

                                            <div class="tasks-widget tasks-1-{{ $day }} {{ $day == $currentWeekDay ? '': 'disp-none' }}">
                                                <div class="panel-body">
                                                    <div class="task-content">
                                                        <ul class="task-list">
                                                            @foreach ($trainings as $training)
                                                                <li>
                                                                    <div class="task-checkbox">
                                                                        <input id="op{{ $training->id }}" name='training[{{ $training->id }}]' type='checkbox' value="{{ $training->id }}" {{ ($training->isDone($weekStartDate)) ? 'checked="true"' : '' }} />
                                                                    </div>
                                                                    <div class="task-title">
                                                                        <span class="task-title-sp">{{ $training->name }}</span>
                                                                        @if ($training->isDone($weekStartDate))
                                                                            <span class="badge bg-theme">{{ __('Done') }}</span>
                                                                        @else
                                                                            <span class="badge bg-important">{{ __('Pending') }}</span>
                                                                        @endif

                                                                        @if ($training->browse_file)
                                                                            <div class="pull-right fs18">
                                                                                <label for="browse-file-{{ $training->id }}-1-{{ $day }}" class="custom-file-upload">
                                                                                    <i class="fa fa-cloud-upload"></i> {{ __('Browse File') }}
                                                                                </label>

                                                                                <input type="file" class="form-control browse-file" id="browse-file-{{ $training->id }}-1-{{ $day }}" name="browse_file[{{ $training->id }}]" accept="image/*">
                                                                            </div>
                                                                        @endif
                                                                        @if (!empty($training->clientTraining($weekStartDate)->first()->browse_file))
                                                                            <div class="pull-right fs18">
                                                                                <a href="{{ $training->clientTraining($weekStartDate)->first()->browse_file }}" target="__blank">
                                                                                    <label style="cursor: pointer;">
                                                                                        {{ __('View') }}&nbsp;&nbsp;
                                                                                    </label>
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                                <input type="hidden" name="wholeDayTrainings[{{ $training->id }}]" value="{{ $training->id }}">
                                                                <input type="hidden" name="day[{{ $training->id }}]" value="{{ $day }}">
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
                </div>
            </div>
        </div>
    </section>
@endsection
