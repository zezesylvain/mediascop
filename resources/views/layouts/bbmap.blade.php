<!doctype html>
<html class="no-js" lang="en">
<head>
    @include("layouts.billboardmap._head")
</head>
<?php
$latDefault = $localite['latitude'] ?? 5.356149786699246;
$lngDefault = $localite['longitude'] ?? -4.007166835937483;
?>

<body>

    @include("layouts.billboardmap._header")

<div class="container-fluid">
    <div class="row row-offcanvas row-offcanvas-left">
        <!-- MAIN CONTENT -->
        <div class="col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0 main">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-sm-12">
                            @includeIf('woody.Html.afficherMessage')
                        </div>
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    @yield('menu')
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- END col -->
            </div>

        </div> <!-- END Col -->
    </div> <!-- END Row -->
     @include("layouts.billboardmap._footer")
</div> <!-- END Container -->
    <div style="visibility: hidden;" class=""></div>
@include("layouts.billboardmap._script")
</body>
</html>
