@inject("func","App\Http\Controllers\core\FunctionController")
<div class="static-table-list">
    <table class="table table-responsive table-striped table-bordered table-hover">
        <tbody>
        <tr>
            <td><b>SECTEUR :</b> {{$campagneInfos[0]->secteur}}</td>
            <td><b>ANNONCEUR :</b>  {{$campagneInfos[0]->raisonsociale}}</td>
        </tr>
        <tr>
            <td colspan="1">
                <b>OPERATION :</b>
                <span>
                    {!!  $func->xEditable ($dbTable ('DBTBL_CAMPAGNETITLES','dbtable'),'operation',$campagnetitle,
                    'select',$campagneInfos[0]->operation) !!}
                    </span>
            </td>
            <td>
                <b>TYPE PROMO :</b>
                <span>
                    {!!  $func->xEditable ($dbTable ('DBTBL_CAMPAGNETITLES','dbtable'),'type_promo',$campagnetitle,'select',$campagneInfos[0]->type_promo) !!}

                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>CAMPAGNE :</b>
                <span>
                    {!!  $func->xEditable ($dbTable ('DBTBL_CAMPAGNETITLES','dbtable'),'title',$campagnetitle,'text',$campagneInfos[0]->title) !!}
                </span>
            </td>
        </tr>
        </tbody>
    </table>
</div>
