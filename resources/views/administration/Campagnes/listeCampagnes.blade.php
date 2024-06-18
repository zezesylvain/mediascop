<div class="static-table-list">
    <table class="table table-responsive table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Média</th>
            <th>Format</th>
            {{--<th>Nature</th>--}}
            <th>Cible</th>
            <th>Divers</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $r)
            <tr>
                <td>{!! $r['media'] !!}</td>
                <td>{!! $r['format'] !!}</td>
                {{--<td>{!! $r['nature'] !!}</td>--}}
                <td>{!! $r['cible'] !!}</td>
                <td>{!! $r['divers'] !!}</td>
                <td>{!! $r['action'] !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
