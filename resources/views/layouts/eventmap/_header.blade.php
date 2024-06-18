<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background-color: rgba(12,12,12,0.2);">
    <div class="container-fluid">
        <!-- Hamburger Menu and Brand (Logo) -->
        <div class="navbar-header">
            <div class="row row-offcanvas row-offcanvas-left">
                <div class="col-xs-12 cadre">
                    <a class="navbar-brand pull-right navbar-toggle collapsed" href="#"><i class="fa fa-info-circle"></i></a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="{{asset("template/img/logo/logo-eventmap.png")}}" alt="BillboardMap" class="img-responsive"></a>
                </div>
            </div>
                    
        </div>
        <div class="navbar-collapse collapse">
            <div class="nav navbar-nav navbar-right">
                <a class="navbar-brand pull-right hidden-xs logo-orange" href="#">
                    <img src="https://nsc.nsconsulting.ci/nsc/images/logo.jpg" alt="NS Cnsulting" class="img-responsive">
                </a>
                <!-- Global Navigation -->
                <ul class="nav navbar-nav  navbar-right">
                    @include("layouts.billboardmap._menu")
                </ul>
            </div>
        </div>
    </div>
</div>

