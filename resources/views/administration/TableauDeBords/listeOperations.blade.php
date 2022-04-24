    <hr class="trait-bleu">
    <div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">
        <h4></h4>
        <div class="data-table-area mg-tb-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline30-list">
                            <div class="sparkline30-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                           data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" >
                                        <thead>
                                        <tr>
                                            <th style="width: 10%;">Date</th>
                                            <th style="width: 20%;">Secteur</th>
                                            <th style="width: 25%;">Annonceur</th>
                                            <th style="width: 45%;">Op&eacute;ration</th>
                                            <th> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($dataTableData as $rowvalue)
                                            <tr>
                                                <td>
                                                    {{$date2Fr ($rowvalue['adddate'],"d/m/Y  H:m")}}
                                                </td>
                                                <td>
                                                    {{$rowvalue['secteurname']}}
                                                </td>
                                                <td>
                                                    {{$rowvalue['raisonsociale']}}
                                                </td>
                                                <td>
                                                    {{--Mettre les id pour les profils avec un level 100--}}
                                                    {{$rowvalue['operationname']}} ({{$rowvalue['id']}})
                                                </td>
                                                <td>
                                                    <a href="{{route ('saisie.creerCampagne',[$rowvalue['id']])}}" title="Supprimer opération!"><i class="fa fa-trash"></i> </a>
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
