@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Clients') }}</h3>
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
                <form class="form-inline search-form" method="__GET" action="{{ route('folders.index') }}">
                    <div class="">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="{{ __('Search by name') }}" value="{{ $request->get('s', '') }}">
                            <select class="form-control" name="t">
                                <option value="">{{ __('Tags') }}</option>

                                @if (!empty($tags))
                                    @foreach ($tags as $index => $tag)
                                        <option value="{{ $tag->id }}" {{ $request->get('t', '') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if($isFiltered == true)
                                <a href="{{route('folders.index')}}" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="pull-right add-new-button">
                        <a class="btn btn-primary" href="{{ route('folders.create') }}"><i class="fa fa-plus"></i></a>
                    </div>
                </form>

            </div>
        </div>

        <br />

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
                                            {{ __('Name') }}
                                        </th>
                                        <th>
                                            {{ __('Tags') }}
                                        </th>
                                        <th>
                                            {{ __('Notes') }}
                                        </th>
                                        <th>
                                            {{ __('Qty') }}
                                        </th>
                                        <th>
                                            {{ __('Created By') }}
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
                                                    @php
                                                        $tags = ['-'];
                                                        if (!empty($record->clientTags) && !$record->clientTags->isEmpty()) {
                                                            $tags = [];
                                                            foreach ($record->clientTags as $clientTag) {
                                                                $tags[] = $clientTag->tag->name;
                                                            }
                                                        }
                                                    @endphp
                                                    <td>{{ implode(", ", $tags) }}</td>
                                                    <td>{{ $record->notes }}</td>
                                                    <td>{{ $record->qty }}</td>
                                                    <td>{{ $record->userCreatedBy->name }}</td>
                                                    <td class="form-inline">
                                                        <a href="{{ route('folders.edit', $record->id) }}" title="{{ __('Edit') }}">
                                                            <i class="fa fa-edit fa-2x"></i>
                                                        </a>
                                                        &nbsp;
                                                        <form action="{{ route('folders.destroy', $record->id) }}" method="POST">
                                                            @method('DELETE')
                                                            @csrf
                                                            <a href="#" class="deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete')}}"><i class="fa fa-trash fa-2x"></i></a>
                                                        </form>
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
            </div>
        </div>
    </section>
@endsection
