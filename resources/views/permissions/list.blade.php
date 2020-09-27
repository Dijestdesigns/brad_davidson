@extends('layouts.app')

@section('content')

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Permissions') }}</h3>
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
                <form class="form-inline search-form" method="__GET" action="{{ route('permissions.index') }}">
                    <div class="form-group">
                        <input type="text" name="s" class="form-control searchInput" placeholder="{{__('Search')}}" @if(!empty($term)) value="{{$term}}" @endif>

                        @if(!empty($term))
                            <a href="{{route('permissions.index')}}" class="btn btn-light">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif<button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                    </div>

                    @can('permissions_create')
                        <div class="pull-right add-new-button">
                            <a class="btn btn-primary" href="{{ route('permissions.create') }}"><i class="fa fa-plus"></i></a>
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
                                <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Permissions') }}</h4>
                            </div>
                            <div class="col-md-4">
                                <h5 class="float-right text-muted">
                                    {{__('Showing')}} {{ $permissions->firstItem() }} - {{ $permissions->lastItem() }} / {{ $permissions->total() }} ({{__('page')}} {{ $permissions->currentPage() }} )&nbsp;
                                </h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                    <thead>
                                        <tr>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Display Name')}}</th>
                                            <th>{{__('Group')}}</th>
                                            <th>{{__('Group Slug')}}</th>
                                            <th>
                                                {{ __('Operations') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($permissions->total() == 0)
                                            <tr>
                                                <td colspan="7">{{__('No results found.')}}</td>
                                            </tr>
                                        @else
                                            @foreach($permissions as $permission)
                                                <tr>
                                                    <td>
                                                        @if(auth()->user()->can('permissions_show'))
                                                            <a href="{{route('permissions.show', $permission->id)}}">{{$permission->name}}</a>
                                                        @else
                                                            {{$permission->name}}
                                                        @endif
                                                    </td>
                                                    <td>{{$permission->display_name}}</td>
                                                    <td>{{$permission->group_name}}</td>
                                                    <td>{{$permission->group_slug}}</td>
                                                    <td class="form-inline">
                                                        @can('permissions_show')
                                                            <a href="{{route('permissions.show', $permission->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('View Permission')}}">
                                                                <i class="fa fa-eye fa-2x"></i>
                                                            </a>
                                                        @endcan
                                                        &nbsp;
                                                        @can('permissions_edit')
                                                            <a href="{{route('permissions.edit', $permission->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('Edit Permission')}}">
                                                                <i class="fa fa-edit fa-2x"></i>
                                                            </a>
                                                        @endcan
                                                        &nbsp;
                                                        @can('permissions_delete')
                                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a href="#" class="deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this permission?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete Permission')}}"><i class="fa fa-trash fa-2x"></i></a>
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
                                        {{ $permissions->appends(['s' => $term])->links() }}
                                    @else
                                        {{ $permissions->links() }}
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
