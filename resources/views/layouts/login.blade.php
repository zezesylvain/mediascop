<!doctype html>
<html class="no-js" lang="fr">

<head>
   @include("layouts.auth.head")
</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="color-line"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
{{--
            <div class="back-link back-backend">
                <a href="index.html" class="btn btn-primary">Back to Dashboard</a>
            </div>
--}}
        </div>
    </div>
</div>

@yield("contenu")

@include("layouts.auth.footer")
</body>

</html>
