@extends('layouts.app')

@section('content')

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Resource Create') }}</h3>
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
                    <form class="form-group p-10" method="POST" action="{{ route('resources.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                {{ __('Title') }}

                                <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required>

                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                {{ __('File') }}

                                <input type="file" name="url" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">

                                @if ($errors->has('file'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2">{{ __('Select Clients') }} : </label>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <select name="users[]" multiple="" style="min-width: 150px;">
                                    <option value="0" selected="">{{ __('For All') }}</option>

                                    @if (!empty($clients) && !$clients->isEmpty())
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->fullName }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @if ($errors->has('users.*'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('users.*') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                <a class="btn btn-default" href="{{ route('resources.index') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
