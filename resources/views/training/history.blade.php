@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Training') }}</h3>
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
                <h4>{{ __('Search Form : ') }}</h4>
                <div class="form-inline search-form">
                    <form method="__GET" action="{{ route('training.client.history', $userId) }}">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control datepicker" placeholder="{{ __('Start Date') }}" value="{{ $request->get('s', '') }}" autocomplete="off" />
                            <input type="text" name="f" class="form-control datepicker" placeholder="{{ __('Finish Date') }}" value="{{ $request->get('f', '') }}" autocomplete="off" />

                            @if($isFiltered == true || $request->get('t') == "0")
                                <a href="{{route('training.client.history', $userId)}}" class="btn btn-light">
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
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Trainings') }}</h4>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="float-right text-muted">
                                        {{__('Showing')}} {{ $records->firstItem() }} - {{ $records->lastItem() }} / {{ $records->total() }} ({{__('page')}} {{ $records->currentPage() }} )&nbsp;
                                    </h5>
                                </div>
                            </div>

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

                            @if (!empty($records) && !$records->isEmpty())
                                @foreach ($records as $index => $record)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel {{ strtotime($now->format('Y-m-d')) > strtotime($record->finished_at) ? ($record->is_done == $record::IS_DONE) ? 'panel-success' : 'panel-danger' : 'panel-primary'}} m10">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">{{ date('Y-m-d', strtotime($record->started_at)) . ' To ' . date('Y-m-d', strtotime($record->finished_at)) }}</h3>
                                                    <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
                                                </div>
                                                <div class="panel-body disp-none">
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('Day') }}</th>
                                                                    <th>{{ __('Date') }}</th>
                                                                    <th>{{ __('Training Name') }}</th>
                                                                    <th>{{ __('Image') }}</th>
                                                                    <th>{{ __('Attended') }}</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @php
                                                                    $clientTrainings = $record->clientTrainings;
                                                                @endphp

                                                                @if (!empty($clientTrainings))
                                                                    @foreach ($clientTrainings as $clientTraining)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $clientTraining->day }}
                                                                            </td>
                                                                            <td>
                                                                                {{ date('Y-m-d', strtotime($clientTraining->date)) }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $clientTraining->training->name }}
                                                                            </td>
                                                                            <td>
                                                                                @if (!empty($clientTraining->browse_file))
                                                                                    <a href="{{ $clientTraining->browse_file }}" target="__blank">
                                                                                        <img src="{{ $clientTraining->browse_file }}" height="50" width="50" />
                                                                                    </a>
                                                                                @else
                                                                                    {{ __('-') }}
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge {{ $clientTraining->is_attended == $clientTraining::IS_ATTENDED ? 'bg-info' : 'bg-important' }}">
                                                                                    <i class="fa {{ $clientTraining->is_attended == $clientTraining::IS_ATTENDED ? 'fa-check-circle' : 'fa-close' }}"></i>
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" class="text-center">
                                                                            <mark>{{ __('No record found.') }}</mark>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

                            <div class="float-left ml-10">
                                @if(!empty($request))
                                    {{ $records->appends($request->all())->links() }}
                                @else
                                    {{ $records->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
