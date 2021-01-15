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
                <form class="form-inline search-form" method="__GET" action="{{ route('logs.index') }}">
                    <div>
                        <div class="form-group">
                            <!-- <input type="text" name="s" class="form-control" placeholder="{{ __('Search by message') }}" value="{{ $request->get('s', '') }}"> -->
                            <input type="date" name="d" class="form-control" value="{{ $request->get('d') }}">

                            <select name="t" class="form-control">
                                <option value="">{{ __('Select') }}</option>

                                <option value="tag" {{ ($request->get('t') == 'tag') ? 'selected' : '' }}>{{ __('Tag') }}</option>
                                <option value="item" {{ ($request->get('t') == 'item') ? 'selected' : '' }}>{{ __('Item') }}</option>
                                <option value="client" {{ ($request->get('t') == 'client') ? 'selected' : '' }}>{{ __('Client') }}</option>
                            </select>

                            @if($isFiltered == true)
                                <a href="{{route('logs.index')}}" class="btn btn-light">
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
                        <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Logs') }}</h4>
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
                                <th>
                                    {{ __('Message') }}
                                </th>
                                <th>
                                    {{ __('URL') }}
                                </th>
                                <th>
                                    {{ __('IP Address') }}
                                </th>
                                <th>
                                    {{ __('User Agent') }}
                                </th>
                                <th>
                                    {{ __('Created At') }}
                                </th>
                                <th>
                                    {{ __('Data') }}
                                </th>
                            </thead>

                            <tbody>
                                @if (!empty($records) && !$records->isEmpty())
                                    @foreach ($records as $index => $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->userCreatedBy->name . ' ' . $record->message }}</td>
                                            <td>{{ $record->url }}</td>
                                            <td>{{ $record->ip_address }}</td>
                                            <td>{{ $record->user_agent }}</td>
                                            <td>{{ $record->created_at }}</td>
                                            <td>
                                                <a href="#" class="showLogBtn" data-toggle="tooltip" data-html="log-data-{{ $record->id }}" data-placement="top" title="{{__('See Data')}}"><i class="fa fa-eye fa-2x"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">
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

    @if (!empty($records) && !$records->isEmpty())
        @foreach ($records as $index => $record)
            <div class="log-data-{{ $record->id }} d-none">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                {{ __('Old Data') }} : 
                                <br />
                                <span class="h4"><pre>{{ $record->old_data }}</pre></span>
                            </div>
                        </div>

                        <hr />
                        <div class="row">
                            <div class="col-md-12">
                                {{ __('New Data') }} : 
                                <br />
                                <span class="h4"><pre>{{ $record->new_data }}</pre></span>
                            </div>
                        </div>

                        <hr />
                        <div class="row">
                            <div class="col-md-12">
                                {{ __('Difference') }} : 
                                <br />
                                <span class="h4"><pre>{{ $record->getDifferences() }}</pre></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection
