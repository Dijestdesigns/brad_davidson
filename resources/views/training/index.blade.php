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
                    <form method="__GET" action="{{ route('training.index') }}">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="{{ __('Search by name') }}" value="{{ $request->get('s', '') }}">

                            @if (!empty(App\Training::$isDaily))
                                <select class="form-control" name="t">
                                    <option value="">{{ __('Select') }}</option>

                                    @foreach ((array)App\Training::$isDaily as $value => $text)
                                        <option value="{{ $value }}" {{ $request->has('t') && $request->get('t') === (string)$value ? 'selected="true"' : '' }}>{{ $text }}</option>
                                    @endforeach
                                </select>
                            @endif

                            @if($isFiltered == true || $request->get('t') == "0")
                                <a href="{{route('training.index')}}" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </form>

                    @can('constant_update')
                        @if (false && defined('TRAINING_CYCLE_DAYS'))
                            <form method="POST" action="{{ route('constants.update', TRAINING_CYCLE_DAYS_ID) }}">
                                @method('PATCH')
                                @csrf

                                <div class="pull-right">
                                    &nbsp;
                                    <input type="number" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" name="value" value="{{ TRAINING_CYCLE_DAYS }}" placeholder="{{ __('Cycle Days') }}" style="width: 30%;">
                                    @if ($errors->has('value'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('value') }}</strong>
                                        </span>
                                    @endif

                                    <input type="hidden" name="key" value="{{ TRAINING_CYCLE_DAYS_KEY }}">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                </div>
                            </form>
                        @endif
                    @endcan

                    @can('training_create')
                        <div class="pull-right add-new-button">
                            <a class="btn btn-primary" href="{{ route('training.create') }}"><i class="fa fa-plus"></i></a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-panel">
                            <div class="col-md-8">
                                <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Trainings') }}</h4>
                            </div>
                            <div class="col-md-4">
                                <h5 class="float-right text-muted">
                                    {{__('Showing')}} {{ $records->firstItem() }} - {{ $records->lastItem() }} / {{ $records->total() }} ({{__('page')}} {{ $records->currentPage() }} )&nbsp;
                                </h5>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-stripped">
                                    <thead>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            {{ __('Name') }}
                                        </th>
                                        <th>
                                            {{ __('Type') }}
                                        </th>
                                        <th>
                                            {{ __('Browse File ?') }}
                                        </th>
                                        <th>
                                            {{ __('Operations') }}
                                        </th>
                                    </thead>

                                    <tbody>
                                        @if (!empty($records) && !$records->isEmpty())
                                            @foreach ($records as $index => $record)
                                                <tr>
                                                    <td>{{ $record->id }}</td>
                                                    <td>{{ $record->name }}</td>
                                                    <td>
                                                        @if ($record->is_daily == App\Training::IS_NOT_DAILY)
                                                            {{ $record->day_from . ' - ' . $record->day_to }}
                                                        @else
                                                            {{ __('Daily') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ (!empty(App\Training::$isBrowseFile[$record->browse_file])) ? App\Training::$isBrowseFile[$record->browse_file] : '-' }}</td>
                                                    <td class="form-inline">
                                                        @can('training_edit')
                                                            <a href="{{ route('training.edit', $record->id) }}" title="{{ __('Edit') }}">
                                                                <i class="fa fa-edit fa-2x"></i>
                                                            </a>
                                                        &nbsp;
                                                        @endcan
                                                        @can('training_delete')
                                                            <form action="{{ route('training.destroy', $record->id) }}" method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a href="#" class="deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete')}}"><i class="fa fa-trash fa-2x"></i></a>
                                                            </form>
                                                        @endcan
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
        </div>

        <hr />

        @can('training_access')
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content-panel">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Clients') }}</h4>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="float-right text-muted">
                                        {{__('Showing')}} {{ $clients->firstItem() }} - {{ $clients->lastItem() }} / {{ $clients->total() }} ({{__('page')}} {{ $clients->currentPage() }} )&nbsp;
                                    </h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-stripped">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center">
                                                    <h3>{{ __('Client Training Informations') }}</h3>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    {{ __('Name') }}
                                                </th>
                                                <th>
                                                    {{ __('Role') }}
                                                </th>
                                                @can('training_info_access')
                                                    <th>
                                                        {{ __('Operations') }}
                                                    </th>
                                                @endcan
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if (!empty($clients) && !$clients->isEmpty())
                                                @foreach ($clients as $index => $client)
                                                    <tr>
                                                        <td>{{ $client->id }}</td>
                                                        <td>{{ $client->fullname }}</td>
                                                        <td>{{ @$client->getRoleNames()[0] }}</td>
                                                        @can('training_info_access')
                                                            <td class="form-inline">
                                                                <a href="{{ route('training.client.history', $client->id) }}" title="{{ __('History') }}"><i class="fa fa-eye fa-2x"></i></a>

                                                                @can('training_info_create')
                                                                    &nbsp;
                                                                    <a href="javascript:void(0);" class="createTrainings" title="{{ __('Create Trainings') }}" data-html="trainings-create-model-{{ $client->id }}" data-id="{{ $client->id }}"><i class="fa fa-plus fa-2x"></i></a>
                                                                @endcan
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <mark>{{ __('No record found.') }}</mark>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                    <div class="float-left ml-10">
                                        @if(!empty($request))
                                            {{ $clients->appends($request->all())->links() }}
                                        @else
                                            {{ $clients->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </section>

    @can('training_info_create')
        @if (!empty($clients) && !$clients->isEmpty())
            @foreach ($clients as $index => $client)
                <div class="trainings-create-model-{{ $client->id }} d-none">
                    <form action="{{ route('training.client.info.create', $client->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="border-head h3">
                                            {{ __('Training Informations') }}
                                            -
                                            <span class="h4">{{ $client->fullname }}</span>
                                        </div>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>{{ __('Start From') }}</label>

                                        <input type="text" name="started_at" class="form-control datepicker started_at-{{ $client->id }}" required autocomplete="off" />
                                    </div>
                                    <div class="col-md-4">
                                        <label>{{ __('Complete At') }}</label>

                                        <input type="text" name="finished_at" class="form-control datepicker finished_at-{{ $client->id }}" required autocomplete="off" />
                                    </div>
                                    <div class="col-md-4">
                                        <label>{{ __('Total Days') }}</label>

                                        <input type="number" name="total_days" disabled="" value="0" class="form-control total_days-{{ $client->id }}">
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>{{ __('Select Trainings') }}</label>

                                        <select class="form-control" name="training_ids[]" multiple="" required>
                                            <!-- <option value="">{{ __('Select') }}</option> -->

                                            @if (!empty($trainings))
                                                @foreach ($trainings as $training)
                                                    <option value="{{ $training->id }}">{{ $training->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary"><i class="fa fa-save"></i></button>
                                        <button type="button" class="btn btn-secondary btn-default bootbox-cancel close-model" style="float: none;"><i class="fa fa-close"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
        @endif
    @endcan

    @can('training_info_edit')
        @if (!empty($clients) && !$clients->isEmpty())
            @foreach ($clients as $index => $client)
                
            @endforeach
        @endif
    @endcan
@endsection
