@extends('layouts.app')

@section('content')

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Permissions Show') }}</h3>
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
            <div class="content-panel">
                <div class="p-10">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page-title pull-right">
                                <div class="heading">
                                    @can('permissions_edit')
                                        <a href="{{route('permissions.edit', $permission->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                    @endcan

                                    @can('permissions_delete')
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn btn-danger deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this permission?")}}"><i class="fa fa-trash"></i></button>
                                        </form>
                                    @endcan

                                    <a href="{{route('permissions.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Name') }}</div>
                        <div class="col-md-8">
                            {{ $permission->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Display Name') }}</div>
                        <div class="col-md-8">
                            {{ $permission->display_name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Group Name') }}</div>
                        <div class="col-md-8">
                            {{ $permission->group_name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Group Slug') }}</div>
                        <div class="col-md-8">
                            {{ $permission->group_slug }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
