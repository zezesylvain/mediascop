<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="admintab-wrap mg-t-30">
            <ul class="nav nav-tabs custom-menu-wrap custon-tab-menu-style1">
                @php($i = 0)
                @foreach($tabListe as $key => $r)
                    @php($i++)
                    @php($active = $i == 1 ? "active" : "")
                    <li class="{{$active}}">
                        <a data-toggle="tab" href="#Tab{{$key}}" title="Cliquer pour actualiser!"><span class="adminpro-icon adminpro-analytics tab-custon-ic"></span>{{$key}} <i class="fa fa-refresh"></i> </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @php($i = 0)
                @foreach($tabListe as $key => $donnees)
                    @php($i++)
                    @php($active = $i == 1 ? "active" : "")
                    <div id="Tab{{$key}}" class="tab-pane in {{$active}} animated flipInX custon-tab-style1" style="min-height: 600px;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 list-pub-saisie">
                                <hr>
                                <div class="datatable-dashv1-list custom-datatable-overright static-table-list">
                                            <table id="table" class="table dataTables-listing table-bordered" data-toggle="" data-pagination="true" data-search="true" style="vertical-align: middle!important;">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Heure Connexion</th>
                                                    <th>Table</th>
                                                    <th>Données</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php($i = 0)
                                                @foreach($donnees as $r)
                                                    @php($i++)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$date2Fr ($r['created_at'])}}</td>
                                                        <td>{{$r['heureDeConnection']}}</td>
                                                        <td>
                                                            {{$r['bd_table']}}
                                                        </td>
                                                        <td>
                                                            {{$getChampTable ($r['bd_table'],$r['id_ligne'])}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
