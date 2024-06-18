<!DOCTYPE html>
<html lang="fr">
    @include('layouts.administration._head')
    <?php
    //if (isset($map)):
    $latDefault =  isset($localite['latitude']) ? $localite['latitude'] : 5.356149786699246;
    $lngDefault = isset($localite['longitude']) ? $localite['longitude'] :-4.007166835937483;
    //endif;
    ?>
    <body onload="initialize('{{ $latDefault }}','{{ $lngDefault }}')">
    <div class="" style="width: 100%;margin-top: 30px;" >
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="tab-content-details shadow-reset" style="padding: 10px 5px 10px 5px;text-align: justify!important;">
                        @include("woody.Html.afficherMessage")
                        <div class="row">
                            <div class="col-sm-12">
                                @yield('frameContainer')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.administration._script')
    </body>
</html>
