@extends("layouts.admin")
@section("container")
<div class="row">
    {!! $titreHtml("GESTION DES MODULES",2,"","dashbord") !!}
    <div class="col-sm-12">
        <form class="row" method="post" action="{{route("storeModule")}}">
            {{csrf_field()}}
            @if(isset($datas))
                <input type="hidden" name="pid" value="{{$datas['id']}}">
            @endif
            <div class="form-group col-sm-4">
                <label for="name">Libellé</label>
                <input type="text"  name="name"  class="form-control" value="{{$datas['name'] or ''}}"  required id="name">
            </div>
            <div class="form-group col-sm-4">
                <label for="name">Icônes</label>
                <select name="icone"  class="form-control"  required id="icone">
                    <option>Choisir une icône</option>
                    @foreach($icones as $icone)
                        <option value="{{$icone['id']}}">{{$icone['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-2">
                <h5>Activer</h5>
                <label class="radio-inline"><input type="radio" name="activated" checked value="1">OUI</label>
                <label class="radio-inline"><input type="radio" name="activated" value="0">Non</label>
            </div>
            <div class="form-group col-sm-6">
                <label for="description">Description</label>
                <textarea  name="description"  class="form-control editeur"  required id="description">{{$datas['description'] or ''}}</textarea>
            </div>
            {!! \App\Http\Controllers\core\FormController::champSubmit('Soumettre') !!}
        </form>
        <hr>
    </div>
    {!! \App\Http\Controllers\core\ModuleController::makeTable($table) !!}
</div>
@endsection