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
                            <p class="user"><i class="fa fa-users"></i>&nbsp;{{ $clientCount }}</p>
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
@endsection
