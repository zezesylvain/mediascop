@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Modifier mes saisies") !!}
    @php($i = 0)
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="admintab-wrap mg-t-30">
                        <ul class="nav nav-tabs custom-menu-wrap custon-tab-menu-style1">
                            @foreach($lesMedias as $id =>  $r)
                                @if(array_key_exists ($id,$mesSaisies))
                                   <?php
                                        $i++;
                                        $active = $id == session ()->get ('mediaTabActive') ? "active" : "";
                                    ?>
                                    <li class="{{$active}}"><a data-toggle="tab" href="#TabSaisie{{$id}}" title="Cliquer pour actualiser!"  ><span class="adminpro-icon adminpro-analytics tab-custon-ic"></span>{{$r['name']}}  <i class="fa fa-refresh"></i> </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @php($i = 0)
                            @foreach($lesMedias as $id => $r)
                                @if(array_key_exists ($id,$mesSaisies))
                                    <?php
                                    $thDuree = "";
                                    $i++;
                                    $active = $id == session ()->get ('mediaTabActive') ? "active" : "";
                                    $med = $getChampTable ($dbTable ('DBTBL_MEDIAS','db'),$id) ;
                                    if ($med == "TELEVISION" || $med == "RADIO"):
                                        $thDuree = "<th>Dur&eacute;e</th>";
                                    endif;
                                    ?>
                                    <div id="TabSaisie{{$id}}" class="tab-pane in {{$active}} animated flipInX custon-tab-style1">
                                        <br>{!! $titreHtml ("Saisie $med ",4) !!}
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
                                                                    <th width="">Tarif</th>
                                                                    <th width="">Coeff</th>
                                                                    <th width=""></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php($i = 0)
                                                                @foreach($mesSaisies[$id] as $r)
                                                                    @php($i++)
                                                                    <tr>
                                                                        <td>{{$i}}</td>
                                                                        <td>{{$date2Fr ($r['date'])}}</td>
                                                                        @if($med == "TELEVISION" || $med == "RADIO")
                                                                            <td>{{$r['heure']}}</td>
                                                                        @endif
                                                                        <td>{{$getChampTable ($dbTable ('DBTBL_SUPPORTS','db'),$r['support'])}}</td>
                                                                        @php($campagneTitle = $getChampTable ($dbTable ('DBTBL_CAMPAGNES','db'),$r['campagne'],'campagnetitle'))
                                                                        <td>{!! $getChampTable ($dbTable ('DBTBL_CAMPAGNETITLES','db'),$campagneTitle,'title') !!}</td>
                                                                        <td>{{$r['tarif']}}</td>
                                                                        <td>{{$r['coeff']}}</td>
                                                                        <td>
                                                                            <a title="Modifier" href="#" class="btn btn-link" data-toggle="modal" data-target="#PrimaryModalhdbgcl" onclick="sendData('table={{$dbTable ('DBTBL_PUB_NON_VALIDES','db')}}&pk={{$r['id']}}&media={{$id}}','{{route ('ajax.formUpdateSaisie')}}','PrimaryModalhdbgclItem')"><i class="fa fa-edit"></i></a>
    
                                                                            <a title="Supprimer" href="#" class="btn btn-link" data-toggle="modal" data-target="#PrimaryModalhdbgcl" onclick="sendData('table={{\App\Helpers\DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db')}}&pk={{$r['id']}}','{{route ('ajax.deleteModalDatas')}}','PrimaryModalhdbgclItem')"><i class="fa fa-trash"></i></a>

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
