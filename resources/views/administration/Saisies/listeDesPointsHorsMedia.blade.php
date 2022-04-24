<hr class="trait-bleu">
<div class="sparkline8-graph" style="max-height: 200px;overflow-y: auto;">
    <div class="static-table-list">
        <div class="main-sparkline13-hd">
            <h4>Liste des <span class="table-project-n">Points choisis</span></h4>
        </div>
    </div>
    <div class="sparkline13-graph">
        <div class="datatable-dashv1-list custom-datatable-overright">
            <table class="table table-bordered table-sm table-striped">
                <thead>
                <tr>
                    <th data-field="id">N°</th>
                    <th data-field="name" data-editable="true">Ville</th>
                    <th data-field="company" data-editable="true">Commune</th>
                    <th data-field="action">Action</th>
                </tr>
                </thead>
                <tbody>
                @php($i = 1)
                @foreach($listeDesPoints as $key => $r)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$r['ville']}}</td>
                        <td>{{$r['commune']}}</td>
                        <td>
                            <button type="button" data-toggle="tooltip" data-original-title='Supprimer' class="pd-setting-ed" onclick="deletePointsHorsMedia('{{$key}}')">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </td>
                    </tr>
                    @php($i++)
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function deletePointsHorsMedia(key) {
        var url = "{{route ('ajax.deletePointsHorsMedia')}}";
        $.ajax({
            url: url,
            method: 'POST',
            data: {key: key},
            dataType: "JSON",
            success: function (data) {
                $('#listeDesPointsHM').html(data.listeDesPoints);
            }
        });
    }
</script>
