@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Modifier Utilisateur ".$user->name."") !!}
    <div class="col-sm-12">
        <form class="" method="POST" action="{{ route('updateUser') }}">
            {{ csrf_field() }}
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label>Nom et Prénom</label>
                    <input id="name" placeholder="Name" type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}"  autofocus>
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class=" form-group{{ $errors->has('email-disabled') ? ' has-error' : '' }}">
                    <label>Email</label>
                    <input id="email" placeholder="E-Mail Address" type="email" class="form-control" name="email-disabled" value="{{ old('email-disabled', $user->email) }}" disabled>

                    @if ($errors->has('email-disabled'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email-disabled') }}</strong>
                        </span>
                    @endif
                </div>
                <input name="email" type="hidden" value="{{$user->email}}">
                <input name="userid" type="hidden" value="{{$user->id}}">
            </div>
            <div class="col-sm-6">
                <div class=" form-group{{ $errors->has('profil') ? ' has-error' : '' }}">
                    <label for="profil">Profil</label>
                    <select id="profil" class="form-control" name="profil" >
                        <option value="">---------------</option>
                        @foreach($lesprofils as $lesprofil)
                            @if($lesprofil['id'] == $user->profil):
                            <?php $selected = "selected='selected'"; ?>
                            @else
                                <?php $selected = ""; ?>
                            @endif
                            <option {{$selected}} value="{{$lesprofil['id']}}">{{$lesprofil['name']}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('profil'))
                        <span class="help-block">
                            <strong>{{ $errors->first('profil') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4" style="padding-top: 34px;">
                        <a href="#" id="lienItem" onclick="sendData('v=yes','{{route('ajax.reinitPassword')}}', 'formPasswordItem')">Reinitialiser le Mot de passe</a>
                    </div>
                    <div id="formPasswordItem" style="padding-top: 34px;"></div>
                </div>
            </div>
            @php($checked = $user->activated == 1 ? 'checked' : '')

            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-6 form-group{{ $errors->has('begindate') ? ' has-error' : '' }}">
                        <label for="begindate">VALIDE DU(DATE)</label>
                        <input size="10" type="text" class="form-control apresDate" name="begindate" id="begindate"  style="font-size:1.1em" value="{{$user->begindate}}" />

                        @if ($errors->has('begindate'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('begindate') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="col-sm-6 form-group{{ $errors->has('enddate') ? ' has-error' : '' }}">
                        <label for="enddate">AU(DATE)</label>
                        <input size="10" type="text" class="form-control apresDate" name="enddate" id="enddate"  style="font-size:1.1em" value="{{$user->enddate}}" />

                        @if ($errors->has('enddate'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('enddate') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-2" style="padding-top: 30px;">
                <div class=" form-group checkbox-inline {{ $errors->has('activated') ? ' has-error' : '' }}">
                    <label class="checkbox-inline">
                        <input id="activated" type="checkbox" class="" name="activated" value="1" {{$checked}}> Activer compte</label>
                    @if ($errors->has('activated'))
                        <span class="help-block">
                                                <strong>{{ $errors->first('activated') }}</strong>
                                            </span>
                    @endif
                </div>
            </div>
            {!! \App\Http\Controllers\core\FormController::champSubmit("MODIFIER") !!}
        </form>
    </div>
    {!! $listeUsers !!}
@endsection