<hr class="trait-bleu">
<div class="sparkline16-graph">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="admintab-wrap mg-t-30">
                    <ul class="nav nav-tabs custom-menu-wrap custon-tab-menu-style1">
                        @php($i = 0)
                        @foreach($medias as $r)
                                <?php
                                $i++;
                                $active = $r['id'] == $mediaActive ? "active" : "";
                                ?>
                                <li class="{{$active}}"><a data-toggle="tab" href="#TabSaisie{{$i}}" title="Cliquer pour actualiser!"  ><span class="adminpro-icon adminpro-analytics tab-custon-ic"></span>{{$r['name']}}  <i class="fa fa-refresh"></i> </a>
                                </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @php($i = 0)
                        @foreach($medias as $r)
                                <?php
                                $thDuree = "";
                                $i++;
                                $active = $r['id'] == $mediaActive ? "active" : "";
                                $med = $r['name'] ;
                                if ($med == "TELEVISION" || $med == "RADIO"):
                                    $thDuree = "<th>Dur&eacute;e</th>";
                                endif;
                                ?>
                                <div id="TabSaisie{{$i}}" class="tab-pane in {{$active}} animated flipInX custon-tab-style1">
                                    <br>{!! $titreHtml ("Mes saisies $med ",4) !!}
                                    <div>
                                        <div class="datatable-dashv1-list custom-datatable-overright" >
                                            <table class="table table-bordered  table-striped" id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                                   data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id">
                                                <thead>
                                                <tr>
                                                    <th width=""></th>
                                                    <th width="">Date</th>
                                                    {!! $thDuree !!}
                                                    <th width="">Support</th>
                                                    <th>Titre de campagne</th>
                                                    @if($med === 'AFFICHAGE')
                                                        <th>Localité</th>
                                                        <th>Investissement</th>
                                                        <th>Nombre</th>
                                                    @endif
                                                    <th width="">Tarif</th>
                                                    <th width="">Coeff</th>
                                                    <th width=""></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php($j = 0)
                                                @foreach($pubsSaisies[$r['id']] as $rr)
                                                    @php($j++)
                                                    <tr>
                                                        <td>{{$j}}</td>
                                                        <td>{{$date2Fr ($rr['date'])}}</td>
                                                        @if($med === "TELEVISION" || $med === "RADIO")
                                                            <td>{{$rr['heure']}}</td>
                                                        @endif
                                                        <td>{{$getChampTable ($dbTable ('DBTBL_SUPPORTS','db'),$rr['support'])}}</td>
                                                        @php($campagneTitle = $getChampTable ($dbTable ('DBTBL_CAMPAGNES','db'),$rr['campagne'],'campagnetitle'))
                                                        <td>{!! $getChampTable ($dbTable ('DBTBL_CAMPAGNETITLES','db'),$campagneTitle,'title') !!}</td>
                                                        @if($med === 'AFFICHAGE')
                                                            @php($localite = $getChampTable($dbTable('DBTBL_LOCALITES','db'),$localite = $rr['localite']))
                                                            <td>
                                                                {{ $localite ?? '' }}
                                                            </td>
                                                            <td>{{$rr['investissement']}}</td>
                                                            <td>{{$rr['nombre']}}</td>
                                                        @endif

                                                        <td>{{$rr['tarif']}}</td>
                                                        <td>{{$rr['coeff']}}</td>
                                                        <td>
                                                            <a title="Modifier" href="#" class="btn btn-link" data-toggle="modal" data-target="#PrimaryModalhdbgcl" onclick="sendData('table={{$dbTable ('DBTBL_PUB_NON_VALIDES','db')}}&pk={{$rr['id']}}&media={{$r['id']}}','{{route ('ajax.formUpdateSaisie')}}','PrimaryModalhdbgclItem')"><i class="fa fa-edit"></i></a>

                                                            <a title="Supprimer" href="#" class="btn btn-link" data-toggle="modal" data-target="#PrimaryModalhdbgcl" onclick="sendData('table={{$dbTable ('DBTBL_PUB_NON_VALIDES','db')}}&pk={{$rr['id']}}','{{route ('ajax.deleteModalDatas')}}','PrimaryModalhdbgclItem')"><i class="fa fa-trash"></i></a>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
