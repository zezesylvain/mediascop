<!DOCTYPE html>
<html dir="ltr" lang="fr">
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="SemiColonWeb" />

        <!-- Stylesheets
        ============================================= -->
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Raleway:300,400,500,600,700|Crete+Round:400i" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />
        <link rel="stylesheet" href="css/dark.css" type="text/css" />
        <link rel="stylesheet" href="css/font-icons.css" type="text/css" />
        <link rel="stylesheet" href="css/animate.css" type="text/css" />
        <link rel="stylesheet" href="css/magnific-popup.css" type="text/css" />

        <link rel="stylesheet" href="css/responsive.css" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Document Title
        ============================================= -->
        <title>Login - Layout 5 | Canvas</title>

    </head>

    <body class="stretched">

        <!-- Document Wrapper
        ============================================= -->
        <div id="wrapper" class="clearfix">

            <!-- Content
            ============================================= -->
            <section id="content">

                <div class="content-wrap nopadding">

                    <div class="section nopadding nomargin" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0; background: url('{{asset('login/bg.jpg')}}') center center no-repeat; background-size: cover;"></div>

                    <div class="section nobg full-screen nopadding nomargin">
                        <div class="container-fluid vertical-middle divcenter clearfix">

                            <div class="center">
                                <a href="index.html"><img src="images/logo-dark.png" alt="Canvas Logo"></a>
                            </div>

                            <div class="card divcenter noradius noborder" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
                                <div class="card-body" style="padding: 40px;">
                                    <form id="loginForm" name="login-form" class="nobottommargin" action="{{ route('login') }}" method="post">
                                        <h3>Login to your Account</h3>
                                        {!! csrf_field () !!}
                                        <div class="col_full">
                                            <label for="username">{{ __('E-Mail') }}:</label>
                                            <input type="text" id="username" required="" 
                                                class="form-control not-dark {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" 
                                                name="email" placeholder="nom.prenom@nsconsulting.ci" title="Entrez votre email" />
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                                <span class="help-block small">
                                                    {{--Your unique username to app--}}
                                                </span>
                                        </div>

                                        <div class="col_full">
                                            <label class="control-label" for="password">{{ __('Mot de passe') }}</label>
                                            <input type="password" title="Entrezz votre mot de passe" placeholder="******" required="" value="" name="password" id="password" class="form-control  not-dark {{ $errors->has('password') ? ' is-invalid' : '' }}">
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                            <span class="help-block small">
                                                {{--Yur strong password--}}
                                            </span>
                                        </div>

                                        <div class="col_full nobottommargin">
                                            <button class="button button-3d button-black nomargin" id="login-form-submit" name="login-form-submit" value="login">Login</button>
                                            
                                        </div>
                                    </form>

                                    <div class="line line-sm"></div>

                                </div>
                            </div>

                            <div class="center dark"><small>Copyrights &copy; All Rights Reserved by Canvas Inc.</small></div>

                        </div>
                    </div>

                </div>

            </section><!-- #content end -->

        </div><!-- #wrapper end -->

        <!-- Go To Top
        ============================================= -->
        <div id="gotoTop" class="icon-angle-up"></div>

        <!-- External JavaScripts
        ============================================= -->
        <script src="js/jquery.js"></script>
        <script src="js/plugins.js"></script>

        <!-- Footer Scripts
        ============================================= -->
        <script src="js/functions.js"></script>

    </body>
</html>
