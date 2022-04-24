<div class="sparkline13-graph">
    <div class="datatable-dashv1-list custom-datatable-overright">
        <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
               data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" class="table table-responsive table-bordered table-striped">
            <thead>
            <tr>
                <th>MEDIAS</th>
                <th>Pr&eacute;noms,Nom</th>
                <th>NBRE DE SAISIES</th>
                <th>SAISIES EN ATTENTE</th>
                <th>VISUALISER</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tab['saisieValidees'] as $med => $r)
                <?php
                $media = $getChampTable($dbTable ('DBTBL_MEDIAS','db'),$med)
                ?>
                @foreach($r as $us => $rr)
                    <?php
                    $user = $getChampTable($tUser,$us) ;
                    $indicateurMM = 0 ;
                    if(array_key_exists('saisieNonValidees',$tab)):
                        if(array_key_exists($med,$tab['saisieNonValidees'])):
                            if(array_key_exists($us,$tab['saisieNonValidees'][$med])):
                                $indicateurMM = $tab['saisieNonValidees'][$med][$us] ;
                            endif;
                        endif;
                    endif;
                    ?>
                    <tr>
                        <td>{!! $media !!}</td>
                        <td>{{ $user }}</td>
                        <td>{{$rr}}</td>
                        <td>{{ $indicateurMM }}
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</div>
