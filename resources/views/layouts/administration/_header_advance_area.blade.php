<div class="header-top-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="header-top-wraper">
                    <div class="row">
                        <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                            <div class="menu-switcher-pro">
                                <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                                    <i class="fa fa-bars"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                            <div class="header-top-menu tabl-d-n">
                                @include("layouts.administration._menu_top")
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="header-right-info">
                                @include("layouts.administration._header_right_infos")
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu start -->
<div class="mobile-menu-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        @include("layouts.administration._menu_mobile")
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu end -->

@yield("breadcome-area")
