<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Hamburger Menu and Brand (Logo) -->
        <div class="navbar-header">
            <a class="navbar-brand pull-right navbar-toggle collapsed" href="#">
                <img src="{{asset("template/img/logo/logo.png")}}" alt="<?php //echo $altLogo; ?>" class="img-responsive">
            </a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img src='{{asset("template/img/logo/logo.png")}}' alt="MediaScop">
            </a>
        </div>            <!-- Global Navigation -->
        <div class="navbar-collapse collapse">
            <a class="navbar-brand pull-right hidden-xs logo-orange" href="#">
                <img src="https://nsc.nsconsulting.ci/nsc/images/logo.jpg" alt="NS Cnsulting" class="img-responsive"></a>
            <ul class="nav navbar-nav navbar-right">
                @include("layouts.client._menu")
            </ul>
        </div>
    </div>
</div>
