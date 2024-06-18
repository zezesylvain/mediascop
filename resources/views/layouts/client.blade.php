<!DOCTYPE HTML>
<html>
@include("layouts.client._head")
<body onload="">
@include("layouts.client._header")

<div class="container-fluid">
    <div class="row row-offcanvas row-offcanvas-left">
        <!-- MAIN CONTENT -->
        <div class="col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0 main">
            <!-- Section -->
            <p class="pull-right visible-xs">
                <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Afficher la barre de s&eacute;lection</button>
            </p>
            <div class="row">
                <div class="col-xs-12">
                    <p class="pull-right visible-xs">
                        <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Afficher la barre de s&eacute;lection</button>
                    </p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <!--<div class="btn-position-left">-->
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
    @include("layouts.client._footer")
</div> <!-- END Container -->
<div style="visibility: hidden;" class=""></div>

@include("layouts.client._script")

</body>
</html>
