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
            <div class="col-md-12">
                <div class="content-panel">
                    <div class="col-md-8">
                        <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Support Queries') }}</h4>
                    </div>
                    <div class="col-md-4">
                        <h5 class="float-right text-muted">
                            {{__('Showing')}} {{ $records->firstItem() }} - {{ $records->lastItem() }} / {{ $records->total() }} ({{__('page')}} {{ $records->currentPage() }} )&nbsp;
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-stripped">
                            <thead>
                                <th width="1%">
                                    {{ __('#') }}
                                </th>
                                <th width="10%">
                                    {{ __('User Name') }}
                                </th>
                                <th width="10%">
                                    {{ __('Email') }}
                                </th>
                                <th width="40%">
                                    {{ __('Query') }}
                                </th>
                                <th width="20%">
                                    {{ __('Created At') }}
                                </th>
                                <th width="20%">
                                    {{ __('Is Done ?') }}
                                </th>
                            </thead>

                            <tbody>
                                @if (!empty($records) && !$records->isEmpty())
                                    @foreach ($records as $index => $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->name }}</td>
                                            <td>{{ $record->email }}</td>
                                            <td>{{ $record->query }}</td>
                                            <td>{{ $record->created_at }}</td>
                                            <td class="form-inline">
                                                @if ($record->is_done == $record::IS_DONE)
                                                    <i class="fa fa-check fa-2x" style="color: green;"></i>
                                                @elseif (auth()->user()->can('support_update'))
                                                    <form action="{{ route('support.update', $record->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <a href="#" title="{{ __('Is Done') }}" class="deleteBtn" data-confirm-message="{{ __('Are you sure ?') }}" data-toggle="tooltip" data-placement="top" class="deleteBtn" data-original-title="{{ __('Is Done') }}">
                                                            <i class="fa fa-close fa-2x" style="color: red;"></i>
                                                        </a>
                                                    </form>
                                                @else
                                                    <i class="fa fa-close fa-2x" style="color: red;"></i>
                                                @endif
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
