@extends('layouts.app')

@section('content')

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Roles Show') }}</h3>
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
                                        @can('roles_edit')
                                            <a href="{{route('roles.edit', $role->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                        @endcan

                                        @if($role->id !== 1)
                                            @can('roles_delete')
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger deleteBtn" data-confirm-message="{{__('Are you sure you want to delete this role?')}}"><i class="fa fa-trash"></i></button>
                                                </form>
                                            @endcan
                                        @endif

                                        <a href="{{route('roles.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">{{ __('Name') }}</div>
                            <div class="col-md-8">
                                {{ $role->name }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">{{ __('Permissions') }}</div>
                            <div class="col-md-8">
                                <table class="table table-striped table-bordered permissions_table">
                                    @foreach($groups as $group)
                                        <tr>
                                            <td>
                                                <h6 class="mb-2 font-weight-bold">{{$group['name']}}</h6>
                                                <div>
                                                    @foreach($group['permissions'] as $perm)
                                                        <label class="mr-4">
                                                            @if($role->hasPermissionTo($perm['id'])) 
                                                                <i class="fa fa-plus" style="color: green;"></i>
                                                            @else
                                                                <i class="fa fa-minus" style="color: red;"></i>
                                                            @endif
                                                            {{$perm['display_name'] !== null ? $perm['display_name'] : $perm['name']}}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
