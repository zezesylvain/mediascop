<div class="row">
    @php
        $TabMessages = [
            4 => [
                'alert' => 'success',
                'message' => 'Le ménu a été enrégistré avec succès.',
            ],
            2 => [
                'alert' => 'danger',
                'message' => 'Une erreur est survenue, veuiller recommencer !',
            ],
            3 => [
                'alert' => 'warning',
                'message' => 'Un menu du même nom existe déjas dans ce groupe.',
            ],
            5 => [
                'alert' => 'warning',
                'message' => 'Veuillez renseigner tous les champs du formulaire.',
            ],
            1 => [
                'alert' => 'success',
                'message' => 'Le role a été enrégistré avec succès.',
            ]
        ]
    @endphp

    @if($codeErr != 0 )
        <div class="col-sm-12">
            <div class="alert alert-{{$TabMessages[$codeErr]['alert']}}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>{{$TabMessages[$codeErr]['message']}}</h4>
            </div>
            <hr>
        </div>
    @endif
    @if($codeErr != 4)
        <div class="col-sm-12">
            <div class="alert alert-info">
                <h4>Liaison du role <b style="color: #FF0000;">{{$role[0]->name}}</b> à un menu</h4>
            </div>
            <hr>
        </div>
        <div class="form-group col-sm-12">
            <label for="groupemenu">Groupe de menu</label>
            <select  name="groupemenu"  class="form-control"  required id="groupemenu" onchange="sendData('grpmenu='+this.value+'&action=3&role={{$role[0]->id}}','{{route('ajax.getFormMenu')}}','formMenuItem')">
                <option value="">-----------</option>
                @foreach($grpMenus as $r)
                    <option value="{{$r['id']}}">{{$r['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-12"><div id="formMenuItem"></div></div>
    @endif
</div>
