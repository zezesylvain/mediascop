<div class="row">
    {{--<div class="col-xs-4">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label>Ancien Mot de passe</label>
            <input id="oldpassword" placeholder="ancien mot de passe" type="password" class="form-control" name="oldpassword" >

            @if ($errors->has('password'))
                <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
            @endif
        </div>
    </div>--}}
    <div class="col-xs-6">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label>Nouveau Mot de passe</label>
            <input id="newpassword" placeholder="Nouveau mot de passe" type="password" class="form-control" name="newpassword" >

            @if ($errors->has('password'))
                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
            @endif
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label>Comfirmer Mot de passe</label>
            <input id="password" placeholder="Confirmer mot de passe" type="password" class="form-control" name="password" >

            @if ($errors->has('password'))
                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
            @endif
        </div>
    </div>
</div>