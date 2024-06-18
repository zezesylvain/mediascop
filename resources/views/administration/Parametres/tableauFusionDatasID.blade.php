<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 5px;padding-bottom: 5px;border: 1px #000000 solid;min-height: 200px;">
    <form action="" method="post" id="formFusionID">
        {!! csrf_field () !!}
        <input type="hidden" name="tableID" value="{{$tableID}}">
        <table id="table" class="table table-bordered table-striped" data-toggle="" data-pagination="true" data-search="true" style="vertical-align: middle!important;">
            <thead>
            <tr>
                @foreach ($dataTableHeader  AS $v => $d)
                    <th>{!! $formatTableHeader($d) !!}</th>
                @endforeach
                <th>Id à conserver</th>
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
                        <input type="radio" name="id" value="{{$row['id']}}">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" type="submit">Valider la fusion</button>
    </form>
</div>
<script>
    
    $('#formFusionID').submit(function (e) {
        e.preventDefault();
        var url = "{{route ('ajax.traiterFormFusion')}}";
        $.ajax({
           url: url,
           method: 'POST',
           data: new FormData(this),
           dataType: "JSON",
           contentType: false,
           cache: false,
           processData: false,
           success: function (resultat) {
                ui.notify("Alerte Notification",resultat.message).closable().hide(4000).effect('slide');
                $('#listeDesIds').html(resultat.formFusionID);
            }
        });
    });
</script>
