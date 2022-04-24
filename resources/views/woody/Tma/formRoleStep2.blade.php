<form method="post" action="{{route("traiterFormRole")}}">
    {!! csrf_field() !!}
    <input type="hidden" name="routeID" value="{{$laroute[0]['id']}}">
    <div class="row">
        <div class="col-sm-12">
            <hr>
            <div class="row">
                <div class="col-sm-3 form-group">
                    <label for="nomRole">Nom du rôle ({{$laroute[0]["name"]}})
                        @if(count($roleExist))
                            <b style="color: red;">{{$roleExist[0]->name}}</b>
                        @endif
                    </label>
                    <input type="text" class="form-control" name="name" id="nomRole">
                </div>
                @if(count($routeParam))
                    @foreach($routeParam as $value)
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="">param {{$value->numero}} : {{$value->parametre}}</label><input class="form-control" name="url-{{$value->numero}}" placeholder="{{$value->parametre}}" id="url-{{$value->numero}}">
                            </div>
                        </div>
                    @endforeach
                @endif
                <hr>
                {!! \App\Http\Controllers\core\FormController::champSubmit("Valider") !!}
            </div>
        </div>
    </div>
</form>
