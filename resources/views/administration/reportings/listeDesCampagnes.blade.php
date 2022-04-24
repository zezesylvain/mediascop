<div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">
    <h4>Les Campagnes du <i>{{$date2Fr($dateDeb)}}</i> au <i>{{$date2Fr($dateFin)}}</i> {{$infoSecteur}}</h4>
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
                                        <th style="width: 20%;">Titre</th>
                                        <th style="width: 25%;">Annonceur</th>
                                        <th style="width: 45%;">Op&eacute;ration</th>
                                        <th> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($resultats as $rowvalue)
                                        <tr>
                                            <td>
                                                {{$date2Fr ($rowvalue['Date'],"d/m/Y")}}
                                            </td>
                                            <td>
                                                {{$rowvalue['Titre']}}
                                            </td>
                                            <td>
                                                {{$rowvalue['Annonceur']}}
                                            </td>
                                            <td>
                                                {!! $rowvalue['Operation'] !!}
                                            </td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#speednewsModal"
                                                   onclick="listeSpeednews('{{$rowvalue['campTitleID']}}')"
                                                   title=""><i class="fa fa-th-list"></i> </a>
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
<script>
    function listeSpeednews(campTitleID){
        var url = "{{route ('ajax.listeSpeednews')}}";
        $.ajax({
            type : "GET",
            url : url ,
            data : {
                campTitleID: campTitleID,
            },
            
            success : function(result){
                $('#listeSpeednewsItem').html(result.tableauSpeednews);
            }
        } )
    }
</script>

<div id="speednewsModal" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-close-area modal-close-df">
                <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
            </div>
            <div class="modal-body">
                <div id="listeSpeednewsItem"></div>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" href="#">Annuler</a>
            </div>
        </div>
    </div>
</div>
