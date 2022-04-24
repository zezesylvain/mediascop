<div class="sparkline13-graph">
    <div class="datatable-dashv1-list custom-datatable-overright">
        <table class="table table-responsive table-bordered table-striped">
            <thead>
            <tr>
                <th>MEDIAS</th>
                @foreach($medias as $r)
                    <th>{{$r['name']}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>Nbre</th>
                @foreach($medias as $r)
                    @if(!array_key_exists($r['id'], $saisies))
                        <th>0</th>
                    @else
                        <th>{{$saisies[$r['id']]}}</th>
                    @endif
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>
</div>
