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
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-panel">
                            <div class="col-md-8">
                                <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('notifications') }}</h4>
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
                                            {{ __('Message') }}
                                        </th>
                                        <th>
                                            {{ __('URL') }}
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
                                                    <td>{{ $record->title }}</td>
                                                    <td>{{ $record->message }}</td>
                                                    <td>
                                                        @if (!empty($record->href))
                                                            <a href="{{ $record->href }}" target="__blank">{{ __('Click here to open') }}</a>
                                                        @else
                                                            {{ __('-') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('notifications.read', $record->id) }}" class="deleteBtn" data-toggle="tooltip" data-placement="top" title="{{__('Remove')}}">{{ __('Remove') }}</a>
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
    </section>
@endsection
