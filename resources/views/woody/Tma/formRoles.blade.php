@extends("layouts.admin")
@section("container")
    <div class="row">
        {!! $titreHtml("Gestions  des Rôles",2) !!}
        <div class="col-sm-6 form-group">
            <label for="choix_tables">CHOISIR ROUTES</label>
            <input list="routes" class="form-control" onfocusout="sendData('route='+this.value,'{{route('ajax.makeFormRole')}}','roleFormItem')">

            <datalist id="routes">
                @foreach($routes as $row)
                    <option value="{{$row["id"]}} --- {{$row["name"]}}--- {{$row["uri"]}}">
                @endforeach
            </datalist>
        </div>

        <div class="col-sm-12">
            <div id="roleFormItem"></div>
        </div>
    </div>
    <hr>
    {!! $listeRoles !!}
@endsection