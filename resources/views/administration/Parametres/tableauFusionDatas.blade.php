<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <hr class="trait-bleu">
</div>
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
    <div class="panel panel-primary">
        <h2 class="panel-heading"></h2>
        <div class="panel-body">
            <div class="datatable-dashv1-list custom-datatable-overright static-table-list">
                <table id="table" class="table dataTables-listing table-bordered table-striped" data-toggle="" data-pagination="true" data-search="true" style="vertical-align: middle!important;">
                    <thead>
                    <tr>
                        @foreach ($dataTableHeader  AS $v => $d)
                            <th>{!! $formatTableHeader($d) !!}</th>
                        @endforeach
                        <th>Ajouter Id</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i = 0)
                    @foreach ($donnees AS $row)
                        @php($i++)
                        <tr>
                            @foreach ($dataTableHeader  AS $k => $r)
                                <td>
                                    {!! $row[$r] !!}
                                </td>
                            @endforeach
                            <td>
                                @php($checked = in_array ($row['id'],$ids) ? "checked" : "")
                                <input type="checkbox" name="id-{{$row['id']}}" onclick="addIdFusion(this.value,'{{$tableID}}',this.checked)" value="{{$row['id']}}" {{$checked}}>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="panel panel-primary">
        <h3 class="panel-heading">Liste des Id à fusionner</h3>
        <div class="panel-body">
            <div id="listeDesIds" style="">
                {!! $formListeIDs !!}
            </div>
        </div>
    </div>
</div>

<script>
    function addIdFusion(id,tableID, etat) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var url = "{{route ('ajax.ajouterIdFusion')}}";
        var datas = {
            id : id,
            tableID : tableID,
            etat : etat
        };
        $.ajax({
            url: url,
            method: 'POST',
            data: datas,
            dataType: "JSON",
            success: function (resultat) {
               // ui.notify("Alerte Notification",resultat.message).closable().hide(8000).effect('slide');
                $('#listeDesIds').html(resultat.formFusionID);
            }
        })
    }

</script>
