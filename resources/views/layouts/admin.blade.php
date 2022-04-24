<!doctype html>
<html class="no-js" lang="en">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet"> 
@include("layouts.administration._head")
<style>
    @media (max-width: 767px) {
        .all-content-wrapper {
        margin-left: 0px;
    }
       .header-top-area{
           position: relative;
           left: 0px;
       }
        .logo-pro {
            display: block;
            text-align: center;
            background: #fff;
            font-family: 'Montserrat', sans-serif;
        }
    }
    .sidebar-nav .metismenu ul a {
    padding: 1px 15px 1px 30px;
        padding-right: 15px;
}
</style>
<?php
$latDefault =  isset($localite['latitude']) ? $localite['latitude'] : 5.356149786699246;
$lngDefault = isset($localite['longitude']) ? $localite['longitude'] :-4.007166835937483;
?>
<body onload="initialize('{{$latDefault}}','{{$lngDefault}}')">
<script src="{{asset ("softs/jscolor/jscolor.js")}}"></script>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="left-sidebar-pro">
        @include("layouts.administration._header")
    </div>
    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="{{route ('home')}}"><img class="main-logo" src="{{asset("template/img/logo/logo
                        .png")}}" alt="" style="height: 60px!important;width: 250px!important;"/></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            @include("layouts.administration._header_advance_area")
        </div>

        <div class="container-fluid">
            <div class="row admin text-center">
                <div class="col-md-12">
                    <div class="row">
                        {{--{!! \App\Http\Controllers\Administration\AdminController::flashNotifications() !!}--}}
                        @include("woody.Html.afficherMessage")
                    </div>
                </div>
            </div>
        </div>
        <div class="blog-area mg-tb-15">
            <div class="container-fluid">
                @yield("contenu")
                @yield("container")
            </div>
        </div>
        <div class="footer-copyright-area">
            @include("layouts.administration._footer")
        </div>
    </div>
    @include("layouts.administration._script")
</body>
</html>
