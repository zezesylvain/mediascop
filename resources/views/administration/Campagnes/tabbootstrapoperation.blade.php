<hr class="trait-bleu">
<div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">

        <h4> Les op&eacute;rations cr&eacute;es apr&egrave;s le  {{$date2Fr ($debutop)}}
            |  |  <a href="{{url(route('saisie.diminuer', [session('filter_param.dddebutfop'),session('filter_param.action')]))}}" title="Diminuez d'une semaine"><i class="fa fa-chevron-down"></i></a> | <a href="{{route('saisie.operation')}}">Nouvelle op&eacute;ration</a>
        </h4>
        <div class="data-table-area mg-tb-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline30-list">
                            <div class="sparkline30-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                           data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" >
                                        <thead>
                                        <tr>
                                            <th style="width: 10%;">Ajout&eacute; le</th>
                                            <th style="width: 25%;">Secteur</th>
                                            <th style="width: 25%;">Annonceur</th>
                                            <th style="width: 40%;">Op&eacute;ration</th>
                                            <th> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($datc as $rowvalue)
                                            <?php
                                            $ajoutele = explode(" ", $rowvalue['adddate']);
                                            ?>
                                        <tr>
                                            <td>
                                                {!! $date2Fr($ajoutele['0']) !!}
                                            </td>
                                            <td>
                                                {!! $rowvalue['secteurname'] !!}
                                            </td>
                                            <td>
                                               {!! $rowvalue['raisonsociale'] !!}
                                            </td>
                                            <td>
                                                {!! $rowvalue['operationname'] !!}
                                            </td>
                                            <td>
                                                <input type="radio" name="operation" value="{{$rowvalue['id']}}" id="operation[{{$rowvalue['id']}}]" onChange="sendData('opid={{$rowvalue['id']}}&secteurID={{$rowvalue['secteurID']}}&annonceurID={{$rowvalue['annonceurID']}}', '{{route ('ajax.campagne')}}', 'opitem')" required>
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
