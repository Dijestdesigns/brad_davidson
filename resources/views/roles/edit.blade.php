@extends('layouts.app')

@section('content')

    <section class="wrapper site-min-height">

        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Roles Edit') }}</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    <form class="form-group p-10" method="POST" action="{{ route('roles.update', $role->id) }}">
                        @method('PATCH')
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Name') }}</div>
                            <div class="col-md-8">
                                @if($role->id === 1)
                                    <span class="badge badge-lg badge-secondary text-white">admin</span>
                                @else
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $role->name }}"  autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Permissions') }}</div>
                            <div class="col-md-8">
                                <table class="table table-striped table-bordered permissions_table">
                                    @foreach($groups as $group)
                                        <tr>
                                            <td>
                                                <h6 class="mb-2 font-weight-bold"><label>{{$group['name']}} <input type="checkbox" class="checkall"> </label></h6>

                                                <div>
                                                    @foreach($group['permissions'] as $perm)
                                                        <label class="mr-4">
                                                            <input type="checkbox" name="{{$perm['name']}}" @if($role->hasPermissionTo($perm['id'])) checked @endif>
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

                        <div class="form-group row mb-0">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                <a class="btn btn-default" href="{{ route('folders.index') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
