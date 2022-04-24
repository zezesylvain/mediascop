@inject("func","App\Http\Controllers\core\FunctionController")
<div class="static-table-list">
    <table class="table table-responsive table-striped table-bordered table-hover">
        <tbody>
        <tr>
            <td><b>SECTEUR :</b> {{$campagneInfos[0]['secteur']}}</td>
            <td><b>ANNONCEUR :</b>  {{$campagneInfos[0]['raisonsociale']}}</td>
        </tr>
        <tr>
            <td colspan="2">
                <b>OPERATION :</b>
                <span>
                    {!!  $func->xEditable ($dbTable ('DBTBL_CAMPAGNETITLES','dbtable'),'operation',$campagnetitle,
                    'select',$campagneInfos[0]['operation']) !!}
                    </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>CAMPAGNE :</b>
                <span>
                    {!!  $func->xEditable ($dbTable ('DBTBL_CAMPAGNETITLES','dbtable'),'title',$campagnetitle,'text',$campagneInfos[0]['title']) !!}
                </span>
            </td>
        </tr>
{{--
        @if(!is_null ($campagneInfos[0]['offretelecom']))
            <tr>
                <td colspan="2"><b>OFFRE TELECOM</b> :
                    {!!  \App\Http\Controllers\core\FunctionController::xEditable ($dbTable ('DBTBL_CAMPAGNETITLES','dbtable'),'offretelecom',$campagnetitle,'select',$getChampTable ($dbTable ('DBTBL_OFFRE_TELECOMS','db'),$campagneInfos[0]['offretelecomid'])) !!}
                    </td>
            </tr>
        @endif
--}}
        </tbody>
    </table>
</div>
