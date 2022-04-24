@extends("layouts.admin")
@section("container")
    <h4>Reïnitialiser Votre Mot de passe</h4>
    <form method="post" action="{{route('user.renitMyPassword')}}">
        {{csrf_field()}}
        <div class="col-xs-12">
            <div class="col-xs-4">
                <div class="form-group{{ $errors->has('ancien_mot_de_passe') ? ' has-error' : '' }}">
                    <label>Ancien Mot de passe</label>
                    <input id="oldpassword" placeholder="ancien mot de passe" type="password" class="form-control" name="ancien_mot_de_passe" value="{{ old('ancien_mot_de_passe') }}">

                    @if ($errors->has('ancien_mot_de_passe'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('ancien_mot_de_passe') }}</strong>
                                </span>
                    @endif
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group{{ $errors->has('nouveau_mot_de_passe') ? ' has-error' : '' }}">
                    <label>Nouveau Mot de passe <b style="color: #FF0000;">(Au moins 6 caractères)</b></label>
                    <input id="newpassword" placeholder="Nouveau mot de passe" type="password" class="form-control" name="nouveau_mot_de_passe" value="{{ old('nouveau_mot_de_passe') }}" >

                    @if ($errors->has('nouveau_mot_de_passe'))
                        <span class="help-block">
                            <strong>{{ $errors->first('nouveau_mot_de_passe') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label>Comfirmer Mot de passe </label>
                    <input id="password" placeholder="Confirmer mot de passe" type="password" class="form-control" name="password" value="{{ old('password') }}">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    {!! \App\Http\Controllers\core\FormController::champSubmit("Confirmer") !!}
    </form>

@stop