<!doctype html>
<html class="no-js" lang="en">
<head>
    @include("layouts.billboardmap._head")
</head>
<?php
$latDefault =  isset($localite['latitude']) ? $localite['latitude'] : 5.356149786699246;
$lngDefault = isset($localite['longitude']) ? $localite['longitude'] :-4.007166835937483;
?>

<body>
<script src="{{asset("softs/jscolor/jscolor.min.js")}}"></script>

    @include("layouts.billboardmap._header")

    <div class="container-fluid">
        <div class="row row-offcanvas row-offcanvas-left">            <!-- SIDEBAR -->
            <div class="col-sm-3 col-md-4 sidebar sidebar-offcanvas" id="sidebar" role="navigation">
                <header class="row">
                    <div class="col-sm-4 col-md-4">
                        <h3 class="text-center weight">
                            FAIRE VOTRE SELECTION <i class="fa fa-hand-o-down"></i>
                        </h3>
                    </div> <!-- END .col -->
                </header> <!-- END .row -->
                <div class="row">
                    <div class="col-sm-12">
                        @include("layouts.billboardmap._sidebar")
                    </div> <!-- END .col -->
                </div> <!-- END .row -->
            </div> <!-- END .col .sidebar -->
            <!-- MAIN CONTENT -->
            <div class="col-sm-9 col-sm-offset-3 col-md-8 col-md-offset-4 main">
                <!-- Section -->
                <p class="pull-right visible-xs">
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Afficher la barre de s&eacute;lection</button>
                </p>
                @include('woody.Html.afficherMessage')
                <div class="row">
                    @yield('content')
                </div> <!-- END row -->
            </div> <!-- END Col -->
        </div> <!-- END Row -->
        @include("layouts.billboardmap._footer")
    </div> <!-- END Container -->
    <div style="visibility: hidden;" class=""></div>
@include("layouts.billboardmap._script")
</body>
</html>
