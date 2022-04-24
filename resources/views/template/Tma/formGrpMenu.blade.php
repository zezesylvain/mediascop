@extends("layouts.admin")
@section("container")
    <div class="row">
        <div class="col-sm-12">
            <h3>GESTION DES GROUPES DE MENU</h3>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-pencil"></i> Nouveau groupe de menu</h4><hr>
                    <form class="row" method="post" action="{{route("storeGrpMenu")}}">
                        {{csrf_field()}}
                        @if(isset($datas))
                            <input type="hidden" name="pid" value="{{$datas['id']}}">
                        @endif
                        <div class="form-group col-sm-4">
                            <label for="name">Libellé</label>
                            <input type="text"  name="name"  class="form-control" value="{{$datas['name'] or ''}}"  required id="name">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="name">Icone</label>
                            <select  name="icone"  class="form-control"  required id="name">
                                <option>---------</option>
                                @foreach($icones as $r)
                                    @php($selected = isset($datas) && $datas['icone'] == $r['id'] ? "selected='selected'" : '')
                                    <option {{$selected}} value="{{$r['id']}}">{{$r['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4"></div>
                        {!! \App\Http\Controllers\core\FormController::champSubmit('Soumettre',2) !!}
                    </form>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-th-list"></i> Liste des groupe de menus</h4><hr>
                    {!! $tableauGrpMenu !!}
                </div>
            </div>
        </div>
    </div>
@endsection