    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-responsive table-bordered table-hover">
                <tr>
                    <th style="width: 25%;">Titre</th>
                    <td style="width: 75%;">{!! $res['Titre'] !!}</td>
                </tr>
                <tr>
                    <th>Opération</th>
                    <td>{!! $res['Operation'] !!}</td>
                </tr>
                <tr>
                    <th>Secteur d'activit&eacute;</th>
                    <td>{!! $res['Secteur'] !!}</td>
                </tr>
                <tr>
                    <th>Annonceur</th>
                    <td>{!! $res['Annonceur'] !!}</td>
                </tr>
                @if(count($resfirst))
                    <tr>
                        <th>Premi&egrave;re diffusion</th>
                        <td>
                            dateajout
                            Media sur support
                        </td>
                    </tr>
                    <tr>
                        <th>Derni&egrave;re diffusion</th>
                        <td>
                            datefin
                            Media sur support 
                        </td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            {!! $docCamp !!}
        </div>
    </div>