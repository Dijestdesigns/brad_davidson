@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Inventory') }}</h3>
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
                <form class="form-inline search-form" method="__GET" action="{{ route('inventory.index') }}">
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
                                <a href="{{route('inventory.index')}}" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                            <input type="hidden" name="f" value="{{ $request->get('f') }}" />
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    @can('inventories_create')
                        <div class="pull-right add-new-button">
                            <a class="btn btn-primary" href="{{ route('inventory.create') }}"><i class="fa fa-plus"></i></a>
                        </div>
                    @endcan
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel" style="height: 100%;">
                    <div class="col-md-8">
                        <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Inventories') }}</h4>
                    </div>
                    <div class="col-md-4">
                        <h5 class="float-right text-muted">
                            {{__('Showing')}} {{ $records->firstItem() }} - {{ $records->lastItem() }} / {{ $records->total() }} ({{__('page')}} {{ $records->currentPage() }} )&nbsp;
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt">
            <div class="col-lg-3 second-aside">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#folders">
                                    {{ __('Clients') }}
                                </a>
                            </h4>
                        </div>
                        <div id="folders" class="panel-collapse out">
                            <div class="panel-body white-panel">
                                @foreach ($folders as $folder)
                                    <div class="row white-header" style="{{ ($request->has('f') && $request->get('f') == $folder->id) ? 'background: #b7b7b7;' : '' }}">
                                        <a href="{{ ($request->has('f') && $request->get('f') == $folder->id) ? route('inventory.index', array_merge($request->query(), ['f' => '', 'page' => ''])) : route('inventory.index', array_merge($request->query(), ['f' => $folder->id, 'page' => ''])) }}">
                                            @if (!empty($folder->photo))
                                                <div class="col-md-3 hidden-sm hidden-xs">
                                                    <img src="{{ $folder->photo->photo }}" class="img-circle" width="45" height="45">
                                                </div>
                                            @else
                                                <div class="col-md-3 hidden-sm hidden-xs">
                                                    <img src="{{ asset('img/no-item-image.png') }}" class="img-circle" width="45" height="45">
                                                </div>
                                            @endif

                                            <div class="col-md-9 col-sm-12 col-xs-12 pt11">
                                                {!! ($request->has('f') && $request->get('f') == $folder->id) ? '<b>' : '' !!}
                                                    {{ $folder->name }}
                                                {!! ($request->has('f') && $request->get('f') == $folder->id) ? '</b>' : '' !!}
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9" style="padding-right: 0px;">
                <div class="row">
                    @if (!empty($records) && !$records->isEmpty())
                        @foreach ($records as $index => $record)
                            <div class="col-lg-4 col-md-4 col-sm-4 mb">
                                <div class="content-panel sp">
                                    <div id="blog-bg">
                                        @if ((!empty($record->photos) && count($record->photos) > 1))
                                            <div id="slider-{{ $record->id }}" class="carousel slide" data-ride="carousel" style="margin-top: -15px !important;">
                                                <div class="carousel-inner">
                                                    @foreach ($record->photos as $key => $photo)
                                                        <div class="carousel-item item {{ ($key == 0) ? 'active' : '' }}">
                                                            <img src="{{ (!empty($photo->photo)) ? $photo->photo : asset('img/no-item-image.png') }}" style="width: 100%;height: 260px;padding-top: 15px !important;" />
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <a class="carousel-control-prev" href="#slider-{{ $record->id }}" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                  </a>
                                                  <a class="carousel-control-next" href="#slider-{{ $record->id }}" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                  </a>
                                            </div>
                                        @else
                                            <img src="{{ (!empty($record->photo->photo)) ? $record->photo->photo : asset('img/no-item-image.png') }}" style="width: 100%;height: 245px;" />
                                        @endif
                                        <div class="pull-left">
                                            <div class="blog-title">{{ $record->name }}</div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="blog-title-right base-quantity">{{ __('Qty') }} : {{ $record->qty }} | ${{ $record->value }}</div>
                                        </div>
                                    </div>
                                    <div class="blog-text">
                                        <p>{{ $record->notes }}</p>
                                        <div>
                                            <div class="text-center">
                                                <div class="btn-group">
                                                    @can('inventories_edit')
                                                        <a class="btn btn-primary btn-sm" title="{{ __('Edit') }}" href="{{ route('inventory.edit', $record->id) }}"><i class="fa fa-edit"></i></a>
                                                    @endcan
                                                    @can('inventories_change_quantities')
                                                        <form action="{{ route('inventory.change.quantity', $record->id) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            <button class="changeQuantity btn btn-dark btn-sm" title="{{ __('Change Quantities') }}" data-title="{{ __('Change Quantities') }}" data-value="{{ $record->qty }}"><i class="fa fa-sort-amount-desc"></i></button>

                                                            <input type="hidden" name="qty" id="qty" value="{{ $record->qty }}">
                                                        </form>
                                                    @endcan
                                                    @can('inventories_move_to_folder')
                                                        <form action="{{ route('inventory.moveto.folder', $record->id) }}" method="POST" style="display: inline-block;margin-left: -4px;">
                                                            @csrf
                                                            <button class="btn btn-warning btn-sm moveItem" title="{{ __('Move to folder') }}" data-html="moveto-model-{{ $record->id }}"><i class="fa fa-arrows"></i></button>
                                                        </form>
                                                    @endcan
                                                    @can('logs_access')
                                                        <div style="display: inline-block;margin-left: -4px;">
                                                            <a href="{{ route('logs.index', ['model' => $record->id]) }}" target="__blank" class="btn btn-info btn-sm" title="{{ __('History') }}"><i class="fa fa-history"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('inventories_delete')
                                                        <form action="{{ route('inventory.destroy', $record->id) }}" method="POST" style="display: inline-block;margin-left: -4px;">
                                                            @method('DELETE')
                                                            @csrf
                                                            <a href="#" class="deleteBtn btn btn-danger btn-sm" data-confirm-message="{{__("Are you sure you want to delete this?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete')}}"><i class="fa fa-trash"></i></a>
                                                        </form>
                                                    @endcan
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
                <form action="{{ route('inventory.moveto.folder', $record->id) }}" method="POST">
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
                                    <label>{{ __('What quantity of this inventory do you want to move?') }}</label>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ __('Choose destination folder') }}&nbsp;:&nbsp;</label>

                                    <div class="row col-md-10 col-xs-12">
                                        <input type="number" min="0" max="{{ $record->qty }}" name="amount" class="form-control" placeholder="{{ __('Enter Amount') }}" value="1" />
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
