<div class="panel">
    <div class="panel-heading">
        <h2>CATEGORIE: {{$grpMenu}}</h2>
        <p>The .table-sm class makes the table smaller by cutting cell padding in half:</p>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th></th>
                <th>Titre</th>
                <th>Contenu</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php($i = 0)
            @foreach($docs as $doc)
                @php($i++)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$doc['titre']}}</td>
                    <td>{!! $doc['contenu'] !!}</td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
