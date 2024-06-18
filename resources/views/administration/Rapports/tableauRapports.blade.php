@extends("layouts.admin")
@inject('admin', "App\Http\Controllers\Client\AdminController")
@section("container")
    {!! $titreHtml("Les rapports") !!}
    <div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">
        <div class="data-table-area mg-tb-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <a href="{{route("reporting.nouveauRapport")}}" class="btn btn-primary btn-block">Nouveau rapport</a>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline30-list">
                            <div class="sparkline30-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                           data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" >
                                        <thead>
                                        <tr>
                                            <th>P&eacute;riode</th>
                                            <th>P&eacute;riodicit&eacute;</th>
                                            <th>Secteur</th>
                                            <th>Libell&eacute;</th>
                                            <th><i class="fa fa-gears"></i></th>
                                            <th><i class="fa fa-download"></i></th>
                                        </tr>
                                        </thead>
                                    <tbody>                                           @php($inc = 0)
                                    @foreach ($lesrapports as $rapport)
                                            @php($inc++)
                                            <tr>
                                                <td>{{$rapport['periode']}}</td>
                                                <td>
                                                    <div id="periodicite-{{$rapport['id']}}">
                                                        <a href="#" title="double cliquer pour modifier!" ondblclick="sendData
                                                            ('id={{$rapport['id']}}&value={{$rapport['periodicite']}}&col=periodicite&table={{$database}}&colname=name&tableMedia={{ $dbTable('DBTBL_PERIODICITES','db')}}&pid=periodicite-{{$rapport['id']}}', '{{route('ajax.bdselect')}}', 'periodicite-{{$rapport['id']}}')" >
                                                            {{$rapport['periodicitename']}}
                                                        </a>
                                                    </div>
                                                    
                                                </td>
                                                <td>
                                                    <div id="secteur-{{$rapport['id']}}">
                                                        <a href="#" title="double cliquer pour modifier!"
                                                           ondblclick="sendData
                                                            ('id={{$rapport['id']}}&value={{$rapport['secteur']}}&col=periodicite&table={{$database}}&colname=name&tableMedia={{$dbTable('DBTBL_SECTEURS','db')}}&pid=secteur-{{$rapport['id']}}', '{{route('ajax.bdselect')}}', 'secteur-{{$rapport['id']}}')">
                                                            {{$rapport['secteurname']}}
                                                        </a>
                                                    </div>
                                                </td>
                                               <td>
                                                    <span id='title-{{$rapport['id']}}'  class='cellule' ondblclick = 'inlineMod("{{$database}}","{{$rapport['id']}}", this, "title", "texte")' >
                                                        {{$rapport['title']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('reporting.deleteRapport',[$rapport['id']]) }}">
                                                        <i class="fa fa-trash-o rouge"></i>
                                                    </a>
                                                </td>
                                                <td class="center">
                                                    <?php
                                                    $extfile = explode('.',$rapport['file']);
                                                    $ex = $extfile[count($extfile) - 1];
                                                    $icone = $icon($ex)
                                                    ?>
                                                    <a target='_blank' href='{{route('reporting.getFile',[encrypt($rapport['file'])])}}' title='Télécharger {{$rapport['title']}}'>
                                                        <i class="fa fa-file" {{$icone}}></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
