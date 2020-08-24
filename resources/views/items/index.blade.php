@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Items') }}</h3>
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
                <form class="form-inline search-form" method="__GET" action="{{ route('items.index') }}">
                    <div>
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="{{ __('Search by name') }}" value="{{ $request->get('s', '') }}">
                            <input type="number" name="q" class="form-control" placeholder="{{ __('Search by qty') }}" value="{{ $request->get('q', '') }}">
                            <input type="number" name="v" class="form-control" placeholder="{{ __('Search by value') }}" value="{{ $request->get('v', '') }}">
                            <select class="form-control" name="ml">
                                <option value="">{{ __('Min level') }}</option>

                                @if (!empty($levels))
                                    @foreach ($levels as $index => $level)
                                        <option value="{{ $index }}" {{ $request->get('ml', '') == $index ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <select class="form-control" name="t">
                                <option value="">{{ __('Tags') }}</option>

                                @if (!empty($tags))
                                    @foreach ($tags as $index => $tag)
                                        <option value="{{ $tag->id }}" {{ $request->get('t', '') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if($isFiltered == true)
                                <a href="{{route('items.index')}}" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="pull-right add-new-button">
                        <a class="btn btn-primary" href="{{ route('items.create') }}"><i class="fa fa-plus"></i></a>
                    </div>
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel" style="height: 100%;">
                    <div class="col-md-8">
                        <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Items') }}</h4>
                    </div>
                    <div class="col-md-4">
                        <h5 class="float-right text-muted">
                            {{__('Showing')}} {{ $records->firstItem() }} - {{ $records->lastItem() }} / {{ $records->total() }} ({{__('page')}} {{ $records->currentPage() }} )&nbsp;
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="row">
                    @if (!empty($records) && !$records->isEmpty())
                        @foreach ($records as $index => $record)
                            <div class="col-lg-3 col-md-3 col-sm-3 mb">
                                <div class="content-panel sp">
                                    <div id="blog-bg">
                                        <img src="{{ (!empty($record->photo)) ? $record->photo->photo : asset('img/no-item-image.png') }}" style="width: 100%;height: 150px;" />
                                        <div class="pull-left">
                                            <div class="blog-title">{{ $record->name }}</div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="blog-title-right base-quantity">${{ $record->qty }} | ${{ $record->value }}</div>
                                        </div>
                                    </div>
                                    <div class="blog-text">
                                        <p>{{ $record->notes }}</p>
                                        <div>
                                            <div class="text-center">
                                                <div class="btn-group">
                                                    <a class="btn btn-primary btn-sm" title="{{ __('Edit') }}" href="{{ route('items.edit', $record->id) }}"><i class="fa fa-edit"></i></a>
                                                    <form action="{{ route('items.change.quantity', $record->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button class="changeQuantity btn btn-dark btn-sm" title="{{ __('Change Quantities') }}" data-title="{{ __('Change Quantities') }}" data-value="{{ $record->qty }}"><i class="fa fa-sort-amount-desc"></i></button>

                                                        <input type="hidden" name="qty" id="qty" value="{{ $record->qty }}">
                                                    </form>
                                                    <form action="{{ route('items.moveto.folder', $record->id) }}" method="POST" style="display: inline-block;margin-left: -4px;">
                                                        @csrf
                                                        <button class="btn btn-warning btn-sm moveItem" title="{{ __('Move to folder') }}" data-html="moveto-model-{{ $record->id }}"><i class="fa fa-arrows"></i></button>
                                                    </form>
                                                    <div style="display: inline-block;margin-left: -4px;">
                                                        <button class="btn btn-info btn-sm" title="{{ __('History') }}"><i class="fa fa-history"></i></button>
                                                    </div>
                                                    <form action="{{ route('items.destroy', $record->id) }}" method="POST" style="display: inline-block;margin-left: -4px;">
                                                        @method('DELETE')
                                                        @csrf
                                                        <a href="#" class="deleteBtn btn btn-danger btn-sm" data-confirm-message="{{__("Are you sure you want to delete this?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete')}}"><i class="fa fa-trash"></i></a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12 mb text-center">
                            <mark>{{ __('No record found.') }}</mark>
                        </div>
                    @endif
                </div>
                <div class="float-left ml-10">
                    @if(!empty($request))
                        {{ $records->appends($request->all())->links() }}
                    @else
                        {{ $records->links() }}
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if (!empty($records) && !$records->isEmpty())
        @foreach ($records as $index => $record)
            <div class="moveto-model-{{ $record->id }} d-none">
                <form action="{{ route('items.moveto.folder', $record->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="border-head h3">
                                        {{ __('Moving') }}
                                        <span class="h4">{{ $record->name }}</span>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ __('What quantity of this item do you want to move?') }}</label>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ __('Choose destination folder') }}&nbsp;:&nbsp;</label>

                                    <div class="row col-md-10 col-xs-12">
                                        <input type="number" min="0" max="{{ $record->qty }}" name="amount" class="form-control" placeholder="Enter Amount" value="1" />
                                    </div>
                                    <div class="row col-md-2">
                                        <label style="margin-top: 6px;">&nbsp;<mark>{{ __('of') }}&nbsp;<span style="font-weight: bold;">{{ $record->qty }}</span></mark></label>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="folder" class="form-control">
                                        <option value="">{{ __('Select') }}</option>

                                        @if (!empty($folders) && !$folders->isEmpty())
                                            @foreach ($folders as $folder)
                                                <option value="{{ $folder->id }}" title="{{ $folder->notes }}">{{ $folder->name }}</option>
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
@endsection
