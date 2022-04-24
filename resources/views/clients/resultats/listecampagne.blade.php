
<div class="panel-body">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataTables-listing liste-campagne">
            <thead>
            <tr>
                <th>Date</th>
                <th>Annonceur</th>
                <th>Titre</th>
                <th>M&eacute;dia</th>
                <th>D&eacute;tails</th>
            </tr>
            </thead>
            <tbody>
                
            @foreach ($detailDesCampagnes AS $cid => $row)
                @php
                    $lesDateDeLaCamp = $row['Date'] ;
                    $nc = count($lesDateDeLaCamp) - 1 ;
                    if($nc):
                        sort($lesDateDeLaCamp) ;
                        $periode = "Du $lesDateDeLaCamp[0] au $lesDateDeLaCamp[$nc]" ;
                    else:
                        $periode = $lesDateDeLaCamp[0] ;
                    endif;
                @endphp
                <tr>
                    <td>{!! $periode !!}</td>
                    <td>{!! $row['Annonceur'] !!}</td>
                    <td>{!! $row['Titre'] !!}</td>
                    <td>{!! join("<br>", $row['media']) !!}</td>
                    <td class="center">
                        <a href="{{route('reporting.detailCampagne',$cid)}}" target="_blank" class="" title="Voir  détail campagne!">
                            <i class="fa fa-file-text-o"> </i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    function getCampagneDetail(cid){
        $.ajax({
            type: 'POST',
            url: "{{route('client.detail')}}",
            data: {
                'cid' : cid
            },
            dataType: 'json'
        }).done(function (data) {
            $('#detailCampagneItem').html(data.detail);
        }).fail(function (data) {
            var msgerror = data.responseJSON.message;
        });

    }
</script>
<!-- Modal -->
<div id="listeCampagneModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div id="detailCampagneItem"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
