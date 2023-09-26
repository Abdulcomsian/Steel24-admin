@extends('admin.layouts.main', ['class' => 'off-canvas-sidebar', 'activePage' => 'login', 'title' => __('Steel24')])

@section('content')
<div class="container" style="height: auto;">
    <div class="row align-items-center">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            <form class="form" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="card card-login card-hidden mb-3">
                    <!-- <div class="card-header card-header-primary text-center">
                        <h4 class="card-title"><strong>{{ __('Login') }}</strong></h4>
                    </div> -->
                    <div class="card-body">
                    <div class="header_customer justify-content-center">
                                         <div >
                                <h4 ><strong>{{ __('Login') }}</strong></h4>
                            </div>
                        </div>
                        <p class="card-description text-center">{{ __('Enter your credentials ') }}</p>
                        <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <!-- <i class="material-icons">email</i> -->
                                    </span>
                                </div>
                                <input type="email" name="email" class="form_customer" placeholder="{{ __('Email...') }}"
                                    value="{{ old('email', null) }}" required autocomplete="email" autofocus>
                            </div>
                            @if ($errors->has('email'))
                            <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                            @endif
                        </div>
                        {{-- Username --}}
                        {{-- <div class="bmd-form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">face</i>
                                    </span>
                                </div>
                                <input type="text" name="username" class="form-control" placeholder="{{ __('Username or Email...') }}"
                                    value="{{ old('username', null) }}" required autocomplete="username" autofocus>
                            </div>
                            @if ($errors->has('username'))
                            <div id="username-error" class="error text-danger pl-3" for="username" style="display: block;">
                                <strong>{{ $errors->first('username') }}</strong>
                            </div>
                            @endif
                        </div> --}}
                        <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <!-- <i class="material-icons">lock_outline</i> -->
                                    </span>
                                </div>
                                <input type="password" name="password" id="password" class="form_customer"
                                    placeholder="{{ __('Password...') }}" required autocomplete="current-password">
                            </div>
                            @if ($errors->has('password'))
                            <div id="password-error" class="error text-danger pl-3" for="password"
                                style="display: block;">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                            @endif
                        </div>
                        <div class="form-check mr-auto ml-3 mt-3">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer justify-content-center">
                        <button type="submit" class="btn btn-primary ">{{ __('Log in') }}</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-6">
                    @if (Route::has('admin.password.request'))
                    <a href="{{ route('admin.password.request') }}" class="text-light">
                        <small><b>{{ __('Forgot password?') }}</b></small>
                    </a>
                    @endif
                </div>
                {{-- <div class="col-6 text-right">
                    <a href="{{ route('admin.register') }}" class="text-light">
                        <small><b>{{ __('Create new account') }}</b></small>
                    </a>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
