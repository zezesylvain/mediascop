<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                Votre sélection
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        @if(isset($selection))
                            @include("clients.selection", $selection)
                        @endif
                    </div>
                </div> <!-- END row -->
            </div> <!-- END panel-body -->
        </div> <!-- END panel panel-sucess -->
        <!--                    <h4 class="page-header">R&eacute;sulat de votre S&eacute;l&eacute;ction</h4>-->
    </div> <!-- END col -->
</div> <!--