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
                <form class="form-inline search-form" method="__GET" action="{{ route('stock_values.index') }}">
                    <div class="">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="{{ __('Search by name') }}" value="{{ $request->get('s', '') }}">

                            <input type="number" name="v" class="form-control" placeholder="{{ __('Search by value') }}" value="{{ $request->get('v', '') }}">

                            @if($isFiltered == true)
                                <a href="{{route('stock_values.index')}}" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="content-panel">
                    <div class="col-md-8">
                        <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Inventories') }}</h4>
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
                                    {{ __('#') }}
                                </th>
                                <th width="50%">
                                    {{ __('Name') }}
                                </th>
                                <th>
                                    {{ __('Quantity') }}
                                </th>
                                <th>
                                    {{ __('Values') }}
                                </th>
                                <th>
                                    {{ __('Operation') }}
                                </th>
                            </thead>

                            <tbody>
                                @if (!empty($records) && !$records->isEmpty())
                                    @foreach ($records as $index => $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->name }}</td>
                                            <td>{{ $record->qty }}</td>
                                            <td>${{ number_format($record->value, 2) }}</td>
                                            <td class="form-inline">
                                                <a href="{{ route('inventory.index', ['s' => $record->name, 'v' => $record->value]) }}" title="{{ __('Check') }}" target="__blank">
                                                    <i class="fa fa-eye fa-2x"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">
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
    </section>
@endsection
