@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Inventory summary') }}</h3>
                </div>
            </div>
        </div>
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
        <!-- /row -->
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
                                        {{ __('Supplement Date') }}
                                        {{ $supplements->date }}
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
@endsection
