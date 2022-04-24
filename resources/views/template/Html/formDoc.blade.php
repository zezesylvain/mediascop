<form class="" role="form" method="POST" action="{{ route('storeDoc') }}">
    {{ csrf_field() }}
    <input type="hidden" name="user" value="{{$user}}">
    <div class="row">
        <div class="col-sm-12">
            <fieldset class="form-paiement">
                <legend><b>DOCUMENTATION</b></legend>
                <div class="row">
                    <div class="col-sm-6 form-group{{ $errors->has('groupemenu') ? ' has-error' : '' }}">
                        <label for="groupemenu">GROUPE DE MENU</label>
                        <select class="form-control" name="groupemenu" id="groupemenu"  onchange="sendData('classe='+this.value,'{{route('ajax.choixLv2')}}','lv2Item')" required>
                            <option value="">Choisir un groupe de menu</option>
                            @foreach($grpMenus as $row)
                                <option value="{{$row['id'] or old('groupemenu')}}">{{$row['name']}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('groupemenu'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('groupemenu') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="titre">TITRE</label>
                        <input type="text" name="titre" id="titre" class="form-control">
                    </div>
                    <div class="col-sm-12 form-group">
                        <label for="titre">CONTENU</label>
                        <textarea  name="contenu" id="titre" class="form-control editeur"></textarea>
                    </div>
                    <div class="col-sm-6 form-group">
                        <h4>VALIDER CE CONTENU</h4>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="valider" value="1"> OUI
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="valider" value="0" checked> NON
                            </label>
                        </div>
                    </div>
                    {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
                </div>
            </fieldset>
        </div>
    </div>
</form>