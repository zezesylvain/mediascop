@extends("layouts.client")
@section("content")
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="row">
                <div class="col-xs-6 col-sm-4 col-md-3 col-ld-2 sous-titre-detail-v2">
                    Titre
                </div>
                <div class="col-xs-6 col-sm-8 col-md-9 col-ld-10">
                    <?php echo $re->title ; ?>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3 col-ld-2 sous-titre-detail-v2">
                    Op&eacute;ration
                </div>
                <div class="col-xs-6 col-sm-8 col-md-9 col-ld-10">
                    <?php echo $re->opname ; ?>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3 col-ld-2 sous-titre-detail-v2">
                    Secteur 'activit&eacute;
                </div>
                <div class="col-xs-6 col-sm-8 col-md-9 col-ld-10">
                    <?php echo $re->secname ; ?>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3 col-ld-2 sous-titre-detail-v2">
                    Annonceur
                </div>
                <div class="col-xs-6 col-sm-8 col-md-9 col-ld-10">
                    <?php echo $re->aname ; ?>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3 col-ld-2 sous-titre-detail-v2">
                    Premi&egrave;re diffusion
                </div>
                <div class="col-xs-6 col-sm-8 col-md-9 col-ld-10">
                    <?php echo $resfirst->dateajout ; ?> (<?php echo $resfirst->mname . " sur " . \App\Http\Controllers\Admin\WooDatabaseController::getDataChamps(\App\Http\Controllers\Admin\WooModuleController::dbTable('DBTBL_SUPPORTS'),$resfirst->support,'name'); ?>)
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3 col-ld-2 sous-titre-detail-v2">
                    Derni&egrave;re diffusion
                </div>
                <div class="col-xs-6 col-sm-8 col-md-9 col-ld-10">
                    <?php echo $reslast->datefin ; ?> (<?php echo $reslast->mname . " sur " . \App\Http\Controllers\Admin\WooDatabaseController::getDataChamps(\App\Http\Controllers\Admin\WooModuleController::dbTable('DBTBL_SUPPORTS'),$resfirst->support,'name') ; ?>)
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $slider ;?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <h3>Plan M&eacute;dia de la campagne</h3>
        </div>

        <div class="col-xs-12">
            <button class="btn btn-warning" onclick="javascript:window.close();">Fermer</button>
        </div>
    </div>

@stop