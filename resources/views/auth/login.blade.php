@extends('layouts.app')

@section('content')
<div class="container">
    @push('scripts')
        <script difer src="{{ asset('js/jquery.backstretch.min.js') }}" defer></script>
    @endpush
    <!-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

    <div id="showtime"></div>

    <div class="col-lg-12 col-lg-offset-4">
        <div class="lock-screen">
            <h2>
                <button type="button" class="btn btn-default loginModel" data-toggle="modal" data-target="#loginModel">
                    <i class="fa fa-lock color-white"></i>
                </button>
            </h2>
            <p>{{ __('Login') }}</p>

            <!-- Modal -->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="loginModel" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">{{ __('Welcome Back') }}</h4>
                        </div>
                        <form method="POST" action="{{ route('login') }}#loginModel">
                            <div class="modal-body">
                                @csrf

                                <div class="login-wrap">
                                    <input id="email" type="email" autofocus="" placeholder="{{ __('Email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <br />

                                    <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <br />

                                    <div class="pull-left" style="padding-left: 20px;">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember" class="form-check-label">
                                            <span style="padding-left: 5px;">{{ __('Remember Me') }}</span>
                                        </label>
                                        <span class="pull-right">
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <!-- modal -->
                            </div>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-lg-offset-4">
                                    <button type="submit" class="btn btn-primary btn-xs">
                                        {{ __('Login') }}
                                    </button>
                                    <button data-dismiss="modal" class="btn btn-danger btn-xs" type="button">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- modal -->
      </div>
      <!-- /lock-screen -->
    </div>

    <!-- <form class="form-login" method="POST" action="{{ route('login') }}">
        @csrf

        <h2 class="form-login-heading">{{ __('Login') }}</h2>

        <div class="login-wrap">
            <input id="email" type="email" autofocus="" placeholder="{{ __('Email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <br />

            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <br />

            <div>
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="form-check-label">
                    <span style="padding-left: 15px;">{{ __('Remember Me') }}</span>
                </label>
                <span class="pull-right">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </span>
            </div>
            <br />

            <button type="submit" class="btn btn-theme btn-block">
                {{ __('Login') }}
            </button>
        </div>
        <!-- modal --><!-- 
    </form> -->
</div>
@endsection
