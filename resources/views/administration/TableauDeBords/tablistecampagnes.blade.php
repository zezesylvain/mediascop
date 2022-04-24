@if(count ($tabdata))
    @php($ij = 0)
    <hr class="trait-bleu">
    <form method="post" action="{{route ("dashbord.validerCampTitleForm")}}">
        {{csrf_field()}}
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
                                                <th style="width: 20%;">Annonceur</th>
                                                <th style="width: 25%;">Op&eacute;ration</th>
                                                <th style="width: 45%;">Campagnes</th>
                                                <th>
                                                    @if($valide == 0)
                                                         <input type="checkbox" onChange="checkboxChecker('listcamp', this.checked, '{{count ($tabdata)}}');">
                                                    @endif
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($tabdata as $rowvalue)
                                            <tr>
                                                <td>
                                                    {{$rowvalue['adddate']}}
                                                </td>
                                                <td>
                                                    {!! $rowvalue['raisonsociale'] !!}
                                                </td>
                                                <td>
                                                    {!! $rowvalue['operationname'] !!}
                                                </td>
                                                <td>
                                                    {!! $rowvalue['title'] !!}
                                                </td>
                                                <td>
                                                    @if($valide == 1)
                                                        <a href="{{route ('saisie.creerCampagne',[$rowvalue['id']])}}" title="Modifier la campagne!"><i class="fa fa-pencil"></i> </a>
                                                    @endif
                                                    @if($valide == 0)
                                                        @php($ij++)
                                                            <div id="validebox{{$rowvalue['id']}}">
                                                                <input id="listcamp[{{$ij}}]" type="checkbox" name="listcamp[]" value="{{$rowvalue['id']}}"><br>
                                                                <a href="#plisting" title="Valider le titre de campagne" onclick="sendData('database={{$DATABASE}}&id={{$rowvalue['id']}}&k=campagne', '{{$routeval}}', 'validebox{{$rowvalue['id']}}');" style="color: #0000cc;">
                                                                    <i class="fa fa-check-circle"></i>
                                                                </a>
                                                            </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($valide == 0)
                            {!! \App\Http\Controllers\core\FormController::champSubmit () !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif
