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
                <form class="form-inline search-form" method="__GET" action="{{ route('resources.index') }}">
                    <div class="form-group">
                        <input type="text" name="s" class="form-control searchInput" placeholder="{{__('Search Title')}}" @if (!empty($request->get('s'))) value="{{ $request->get('s') }}" @endif>

                        @if(!empty($term))
                            <a href="{{ route('resources.index') }}" class="btn btn-light">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif

                        @if (!empty($extensions) && !$extensions->isEmpty())
                            <select name="e" class="form-control searchInput">
                                <option value="">{{ __('Select') }}</option>

                                @foreach ($extensions as $extension)
                                    <option value="{{ $extension }}" {{ !empty($request->get('e')) && $request->get('e') == $extension ? 'selected="true"' : '' }}>{{ $extension }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if($isFiltered == true)
                            <a href="{{route('resources.index')}}" class="btn btn-light">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                    </div>

                    @can('resource_create')
                        <div class="pull-right add-new-button">
                            <a class="btn btn-primary" href="{{ route('resources.create') }}"><i class="fa fa-plus"></i></a>
                        </div>
                    @endcan
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
                                <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Resources') }}</h4>
                            </div>
                            <div class="col-md-4">
                                <h5 class="float-right text-muted">
                                    {{__('Showing')}} {{ $resources->firstItem() }} - {{ $resources->lastItem() }} / {{ $resources->total() }} ({{__('page')}} {{ $resources->currentPage() }} )&nbsp;
                                </h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                    <thead>
                                        <tr>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('URL')}}</th>
                                            <th>{{ __('Extension') }}</th>
                                            <th>{{ __('For all ?') }}</th>
                                            <th>
                                                {{ __('Operations') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($resources->total() == 0)
                                            <tr>
                                                <td colspan="5" class="text-center"><mark>{{__('No results found.')}}</mark></td>
                                            </tr>
                                        @else
                                            @foreach($resources as $resource)
                                                <tr>
                                                    <td>{{ $resource->title }}</td>
                                                    <td><a href="{{ $resource->url }}" target="__blank">{{ strlen($resource->url) > 50 ? substr($resource->url, 0, 50) . " ..." : $resource->url }}</a></td>
                                                    <td>{{ $resource->extensions }}</td>
                                                    <td>{{ $resource::$forAll[$resource->for_all] }}</td>
                                                    <td class="form-inline">
                                                        @can('resource_delete')
                                                            <form action="{{ route('resources.destroy', $resource->id) }}" method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a href="#" class="deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this resource?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete Resource')}}"><i class="fa fa-trash fa-2x"></i></a>
                                                            </form>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <div class="float-left ml-10">
                                    @if(!empty($term))
                                        {{ $resources->appends(['s' => $term])->links() }}
                                    @else
                                        {{ $resources->links() }}
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
