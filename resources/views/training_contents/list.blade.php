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
                <form class="form-inline search-form" method="__GET" action="{{ route('trainingContents.index') }}">
                    <div class="form-group">
                        <input type="text" name="s" class="form-control searchInput" placeholder="{{__('Search')}}" @if(!empty($request->get('s'))) value="{{$request->get('s')}}" @endif>

                        @if($isFiltered == true)
                            <a href="{{ route('trainingContents.index') }}" class="btn btn-light">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif

                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
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
                                <h4><i class="fa fa-angle-right"></i>&nbsp;{{ __('Total') }} {{ $total }} {{ __('Roles') }}</h4>
                            </div>
                            <div class="col-md-4">
                                <h5 class="float-right text-muted">
                                    {{__('Showing')}} {{ $roles->firstItem() }} - {{ $roles->lastItem() }} / {{ $roles->total() }} ({{ __('page') }} {{ $roles->currentPage() }} )&nbsp;
                                </h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Users') }}</th>
                                            <th>{{ __('Posts') }}</th>
                                            <th>
                                                {{ __('Operations') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($roles->total() == 0)
                                            <tr>
                                                <td colspan="5" class="text-center"><mark>{{ __('No results found.') }}</mark></td>
                                            </tr>
                                        @else
                                            @foreach($roles as $role)
                                                <tr>
                                                    <td>
                                                        @if(auth()->user()->can('roles_show'))
                                                            <a href="{{route('roles.show', $role->id)}}">{{ $role->name }}</a>
                                                        @else
                                                            {{ $role->name }}
                                                        @endif
                                                     </td>
                                                     <td>{{ count($role->users) }}</td>
                                                     <td>{{ $role->trainingPosts->count() }}</td>
                                                     <td class="form-inline">
                                                        @can('training_content_update')
                                                            <a href="{{ route('trainingContents.edit', $role->id) }}" title="{{ __('Edit') }}">
                                                                <i class="fa fa-edit fa-2x"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <div class="float-left ml-10">
                                    @if(!empty($request))
                                        {{ $roles->appends($request->all())->links() }}
                                    @else
                                        {{ $roles->links() }}
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
