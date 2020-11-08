@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        @include('ultimateLogo')

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    <form class="form-group p-10" action="{{ route('coaching.store') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Name') }} : </label>

                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus="" required="" />

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('Type') }} : </label>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label>
                                            <input type="radio" class="{{ $errors->has('is_daily') ? ' is-invalid' : '' }}" name="is_daily" checked="" value="{{ App\Coaching::IS_DAILY }}" />
                                            {{ __('Daily') }}
                                        </label>
                                        <br />
                                        <label>
                                            <input type="radio" class="{{ $errors->has('is_daily') ? ' is-invalid' : '' }}" name="is_daily" {{ (old('is_daily') == '0') ? 'checked=true' : '' }} value="{{ App\Coaching::IS_NOT_DAILY }}" />
                                            {{ __('Custom Day') }}
                                        </label>

                                        @if ($errors->has('is_daily'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('is_daily') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div id="custom_days" class="{{ (old('is_daily') == '0') ? '' : 'disp-none' }}">
                                        <div class="col-md-4">
                                            <label>{{ __('Day From') }}</label>

                                            <input type="number" class="form-control{{ $errors->has('day_from') ? ' is-invalid' : '' }}" name="day_from" value="{{ old('day_from') }}" />

                                            @if ($errors->has('day_from'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('day_from') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label>{{ __('Day To') }}</label>

                                            <input type="number" class="form-control{{ $errors->has('day_to') ? ' is-invalid' : '' }}" name="day_to" value="{{ old('day_to') }}" />

                                            @if ($errors->has('day_to'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('day_to') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Browse File ?') }}</label>
                                <br />

                                @php
                                    $isChecked = false;
                                @endphp
                                @foreach (App\Coaching::$isBrowseFile as $value => $text)
                                    <label>
                                        @php
                                            if (!$isChecked) {
                                                if (old('browse_file') == $value) {
                                                    $isChecked = true;
                                                } elseif (old('browse_file') === NULL && $value == '0') {
                                                    $isChecked = true;
                                                }
                                            } else {
                                                $isChecked = false;
                                            }
                                        @endphp
                                        <input type="radio" class="{{ $errors->has('browse_file') ? ' is-invalid' : '' }}" name="browse_file" {{ ($isChecked) ? 'checked=true' : '' }} value="{{ $value }}" />
                                        {{ $text }}
                                    </label>

                                    @if ($errors->has('browse_file'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('browse_file') }}</strong>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                <a class="btn btn-default" href="{{ route('coaching.index') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
