
<div class="panel-body">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataTables-listing">
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
                    sort($row['date']) ;
					$route = route('client.detail', $cid);
                @endphp
                <tr>
                    <td>{!! $row['date'][0] !!}</td>
                    <td>{!! $row['Annonceur'] !!}</td>
                    <td>{!! $row['Titre'] !!}</td>
                    <td>{!! join("<br>", $row['media']) !!}</td>
                    <td class="center">
                        <a target="_blank" href="{{$route}}">
                            <i class="fa fa-file-text-o"> </i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
