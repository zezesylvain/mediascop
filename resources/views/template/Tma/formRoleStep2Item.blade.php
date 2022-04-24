<div class="row">
    @php
    $TabMessages = [
        3 => [
            'alert' => 'info',
            'message' => 'Ce role existe déjas',
        ],
        2 => [
            'alert' => 'danger',
            'message' => 'Une erreur est survenue, veuiller recommencer!',
        ],
        4 => [
            'alert' => 'warning',
            'message' => 'Veuillez renseigner le nom du role!',
        ]
    ]
    @endphp

    @if($codeErr != 0)
        <div class="col-sm-12">
            <div class="alert alert-{{$TabMessages[$codeErr]['alert']}}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>{{$TabMessages[$codeErr]['message']}}</h4>
            </div>
            <hr>
        </div>
    @endif
    <div class="col-sm-12">
        <div class="alert alert-info">
            <h2>Nom Route: <b style="color: #FF0000;">{{$libelleRoute}}</b></h2>
        </div>
    </div>
    <div class="col-sm-12 form-group">
        <label for="nomRole">Entrer le Nom du rôle
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
    <div class="col-sm-12 btn-group">
        <a href="#" class="btn btn-block btn-primary" onclick="sendData('role='+$('#nomRole').val()+'&route={{$larouteID}}','{{route('ajax.storeRoleItem')}}','estRoleItem')">Valider</a>
    </div>
    <br>
    <div id="estRoleItem2"></div>
</div>

