@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Supplements') }}</h3>
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

                <form class="form-inline search-form" method="__GET" action="{{ route('supplements.store') }}">
                    <div class="">
                        <div class="form-group">
                            @if(auth()->user()->isSuperAdmin())
                                <select name="u" class="form-control">
                                    <option value="">{{ __('Select') }}</option>

                                    @if(!empty($users) && !$users->isEmpty())
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $request->get('u', false) == $user->id ? 'selected="true"' : '' }}>{{ $user->name . " " . $user->surname }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @endif
                            <input type="text" name="d" class="form-control datepicker" placeholder="yyyy-mm-dd" value="{{ $request->get('d', false) ? date('Y-m-d', strtotime($request->get('d', false))) : '' }}">
                            @if($isFiltered == true || $request->get('c') == "0")
                                <a href="{{route('supplements.index')}}" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    @can('supplements_create')
                        <div class="pull-right add-new-button">
                            <a class="btn btn-primary" href="{{ route('supplements.create') }}"><i class="fa fa-plus"></i></a>
                        </div>
                    @endcan
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="content-panel">
                    <div class="col-md-8">
                        <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Supplements') }}</h4>
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
                                    {{ __('User Name') }}
                                </th>
                                <th>
                                    {{ __('Supplement Date') }}
                                </th>
                                <th colspan="3">
                                    {{ __('Supplements') }}
                                </th>
                                @can('supplements_edit')
                                    <th>
                                        {{ __('Operations') }}
                                    </th>
                                @elsecan('supplements_delete')
                                    <th>
                                        {{ __('Operations') }}
                                    </th>
                                @endcan
                            </thead>

                            <tbody>
                                @if (!empty($records) && !$records->isEmpty())
                                    @foreach ($records as $index => $record)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $record->user->name }}</td>
                                            <td>{{ $record->supplement_date }}</td>
                                            <td colspan="3">
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
                                                                $supplementDatas = $record::where('user_id', $record->user_id)->whereDate("date", $record->supplement_date)->get();

                                                                if (!empty($supplementDatas) && !$supplementDatas->isEmpty()) {
                                                                    $supplementDatas = $supplementDatas->keyBy('row_id');
                                                                }
                                                            @endphp

                                                            @for($rowId = 1; $rowId <= $record::TOTAL_ROWS; $rowId++)
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
                                            </td>
                                            <td class="form-inline">
                                                @can('supplements_edit')
                                                    <a href="{{ route('supplements.edit', [$record->user_id, strtotime($record->supplement_date)]) }}" title="{{ __('Edit') }}">
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a>
                                                    &nbsp;
                                                @endcan
                                                @can('supplements_delete')
                                                    <form action="{{ route('supplements.destroy', [$record->user_id, strtotime($record->supplement_date)]) }}" method="POST">
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
                                        <td colspan="10" class="text-center">
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
