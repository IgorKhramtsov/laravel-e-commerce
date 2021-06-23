@extends('layouts.app')

@section('content')
    <div class="container main">
        <div class="login row">
            <span class="login-error">{{ $errors->first() }}</span>
            <div class="login-form js-login-panel col-md-5">
                <form class="js-form login-form js-login-form" method="post" action="{{ route('login') }}" novalidate >
                    @csrf
                    <div class="input">
                        <input type="text" name="email" id="login" required value="{{ old('email') }}">
                        <label for="login">Логин</label>
                    </div>
                    <div class="input">
                        <input type="password" name="password" id="password" required>
                        <label for="password">Пароль</label>
                        <a class="forgot-pass" href="{{ route('password.request') }}"> Забыли пароль? </a>
                    </div>
                    <div class="material-switch">
                        <span>Запомнить</span>
                        <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember"></label>
                    </div>
                    <button type="submit" class="button" id="js-login">Войти</button>
                </form>

                <form class="js-form register-form js-register-form" method="post" action="{{ route('register') }}" novalidate>
                    @csrf
                    <div class="input">
                        <input type="text" name="name" id="name-reg" value="{{ old('name') }}" required >
                        <label for="name-reg">Имя</label>
                    </div>
                    <div class="input">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                        <label for="email">E-mail</label>
                    </div>
                    <div class="input">
                        <input type="password" name="password" id="password-reg" required>
                        <label for="password-reg">Пароль</label>
                    </div>
                    <div class="input">
                        <input type="password" name="password_confirmation" id="password_confirm" data-rule-equalTo="#password-reg" required>
                        <label for="password_confirm">Повторите пароль</label>
                    </div>
                    <button type="submit" class="button" >Зарегистрироваться</button>
                </form>

            </div>
            <div class="image-container col-md-7">
                <login-image></login-image>
                <button class="button button-outlined" id="login-switch" data-text="Войти">Регистрация</button>
            </div>
        </div>
    </div>
{{--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
--}}
@endsection
