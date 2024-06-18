@extends("layouts.admin")
@section("container")
    <div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">
        <div class="data-table-area mg-tb-15">
            <div class="container-fluid">
                <div class="row">
                    {!! $titreHtml("Valider les opérations") !!}
                    <div class="col-sm-12">
                        <div id="messageItem" class="alert "></div>
                    </div>
                    <form method="post" action="{{route ("store.operation")}}">
                        {{csrf_field()}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="sparkline30-list">
                                    <div class="sparkline30-graph">
                                        <div class="datatable-dashv1-list custom-datatable-overright">
                                            <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                                   data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" >
                                                <thead>
                                                <tr>
                                                    <th style="width: 2%;">N°</th>
                                                    <th style="width: 10%;">Date</th>
                                                    <th style="width: 20%;">Secteur</th>
                                                    <th style="width: 25%;">Annonceur</th>
                                                    <th style="width: 43%;">Op&eacute;ration</th>
                                                    <th>
                                                        <input type="checkbox" onChange="checkboxChecker('listOp', this
                                                            .checked, '{{count ($dataTableData)}}');">
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php($ij = 0)
                                                @foreach ($dataTableData as $rowvalue)
                                                    @php($ij++)
                                                    <tr>
                                                        <td>{{$ij}}</td>
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
                                                            {{$rowvalue['operationname']}} ({{$rowvalue['id']}})
                                                        </td>
                                                        <td>
                                                            <div id="opv-{{$rowvalue['id']}}">
                                                                    <input id="listOp[{{$ij}}]" type="checkbox" name="listOp[]"
                                                                        value="{{$rowvalue['id']}}"><br>
                                                                    <a href="#plisting" title="Valider l'opération"
                                                                       onclick="validerOperation('{{$rowvalue['id']}}')"
                                                                    style="color: #0000cc;">
                                                                        <i class="fa fa-check-circle"></i>
                                                                    </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <hr>
                            {!! \App\Http\Controllers\core\FormController::champSubmit ("Valider") !!}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <script>
       function validerOperation(opID) {
           var url = "{{route ('ajax.validerOperation')}}";
           $.ajax({
               type: "GET",
               url: url,
               data: {
                   opID: opID
               },

               success: function (result) {
                   $('#messageItem').addClass(result.alert).text(result.message)
                   if (result.ok){
                       $('#opv-'+result.opID+'').html("<div><span class='fa fa-check'></span></div>").css('color',
                           'green');
                   }
               }
           })
       }
   </script>
@endsection
