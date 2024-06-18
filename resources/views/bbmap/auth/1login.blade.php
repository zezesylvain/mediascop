@extends('layouts.login')

@section('contenu')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            <div class="col-md-4 col-md-4 col-sm-4 col-xs-12">
                <div class="text-center m-b-md custom-login">
                    <h3>NS CONSULTING
                        -MEDIASCOP</h3>
                    <p>Bases de données - Coaching - Etudes & formations en communication!</p>
                </div>
                <div class="hpanel">
                    <div class="panel-body">
                        <form method="post" action="{{ route('login') }}" id="loginForm">
                            {!! csrf_field () !!}
                            <div class="form-group">
                                <label class="control-label" for="username">{{ __('E-Mail') }}</label>
                                <input type="text" placeholder="example@gmail.com" title="Please enter you username" required="" value="{{ old('email') }}" name="email" id="username" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <span class="help-block small">
                                    {{--Your unique username to app--}}
                                </span>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">{{ __('Mot de passe') }}</label>
                                <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <span class="help-block small">
                                    {{--Yur strong password--}}
                                </span>
                            </div>
                            <div class="checkbox login-checkbox">
                                <label>
                                    <input type="checkbox" class="i-checks" {{ old('remember') ? 'checked' : '' }}>  {{ __('Se Souvenir') }} </label>
                                <p class="help-block small">
                                    {{--(if this is a private computer)--}}
                                </p>
                            </div>
                            <button class="btn btn-success btn-block loginbtn">Login</button>
                            {{--<a class="btn btn-default btn-block" href="#">Register</a>--}}
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <p>Copyright &copy; 2018 <a href="">Mediascop</a> Tous droits réservés.</p>
            </div>
        </div>
    </div>
@endsection
