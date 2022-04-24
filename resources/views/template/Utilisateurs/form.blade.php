@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Créer Utilisateurs",1,"","users") !!}
    <div class="col-sm-12">
        <div class="sparkline12-graph">
            <div class="basic-login-form-ad">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="all-form-element-inner">
                             <form class="form-group-inner" role="form" method="POST" action="{{ route('storeUser') }}">
                                {{ csrf_field() }}
                                @isset($data)
                                    <input type="hidden" name="id" value="{{$data->id}}">
                                @endisset
                                <input type="hidden" name="table" value="{{$table}}">
                                 @if(isset($data))
                                     <div class="col-sm-6 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                         <label for="name">Nom et Prenoms</label>
                                         <input id="name" type="text" class="form-control" name="name"
                                                value="{{ $data->name ?? old('name') }}"  autofocus>
                                         @if ($errors->has('name'))
                                             <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                         @endif
                                     </div>
                                 @else
                                     <div class="col-sm-6 form-group{{ $errors->has('nom') ? ' has-error' : '' }}">
                                         <label for="nom">Nom</label>
                                         <input id="nom" placeholder="Nom" type="text" class="form-control" name="nom" value="{{ $data->nom ?? old('nom') }}"  autofocus>
                                         @if ($errors->has('nom'))
                                             <span class="help-block">
                                                <strong>{{ $errors->first('nom') }}</strong>
                                            </span>
                                         @endif
                                     </div>
                                     <div class="col-sm-6 form-group{{ $errors->has('prenoms') ? ' has-error' : '' }}">
                                         <label for="prenoms">Prénoms</label>
                                         <input id="prenoms" placeholder="Prenoms" type="text" class="form-control" name="prenoms" value="{{ $data->name ?? old('prenoms') }}"  autofocus onfocusout="sendData('nom='+$('#nom').val()+'&prenoms='+$('#prenoms').val(),'{{route('ajax.genererUserEmail')}}','emailItem')">
                                         @if ($errors->has('prenoms'))
                                             <span class="help-block">
                                                <strong>{{ $errors->first('prenoms') }}</strong>
                                            </span>
                                         @endif
                                     </div>
                                 @endif
                                <div class="col-sm-12" id="emailItem">
                                    <div class="row">
                                        @if(!isset($data))
                                            <div class="col-sm-6 form-group">
                                                <label for="email">Email</label>
                                                <input id="email" placeholder="E-Mail Address" type="email" class="form-control" name="email" value="{{ $data->email ?? "" }}" required>
                                            </div>
                                            <div class=" col-sm-6 form-group">
                                                <label for="password">Mot de passe</label>
                                                <input id="password" placeholder="Password" type="password" class="form-control" name="password" required="required" >
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group{{ $errors->has('profil') ? ' has-error' : '' }}">
                                    <label for="profil">Profil</label>
                                    <select id="profil" class="form-control chosen-select" name="profil" >
                                        <option value="">Choisir un profil</option>
                                        @foreach($lesprofils as $lesprofil)
                                            @php($selected = isset($data) && $data->profil == $lesprofil['id'] ? "selected='selected'" : "")
                                            <option value="{{$lesprofil['id']}}" {{$selected}}>{{$lesprofil['name']}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('profil'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('profil') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-sm-4 form-group{{ $errors->has('secteur') ? ' has-error' : '' }}">
                                    <label for="secteur">Secteur</label>
                                    <select id="secteur" class="form-control chosen-select" name="secteur"
                                            onchange="chercherAnnonceur(this.value)">
                                        <option value="">Choisir un secteur</option>
                                        @foreach($secteurs as $r)
                                            @php($selected = isset($secteur) && $secteur === $r['id'] ?
                                            "selected='selected'" : "")
                                            <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('secteur'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('secteur') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-sm-4 form-group{{ $errors->has('annonceur') ? ' has-error' : '' }}">
                                    <label for="annonceur">Société</label>
                                    <select id="annonceurfilteroperation" class="form-control chosen-select" name="annonceur" >
                                        <option value="">Choisir un annonceur</option>
                                        @foreach($annonceurs as $r)
                                            @php($selected = isset($annonceur) && $annonceur === $r['id'] ?
                                            "selected='selected'" : "")
                                            <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                                        @endforeach
                                    </select>
                    
                                    @if ($errors->has('annonceur'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('annonceur') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6 form-group{{ $errors->has('begindate') ? ' has-error' : '' }}">
                                            <label for="begindate">VALIDE DU(DATE)</label>
                                            <input size="10" type="text" class="form-control apresDate" name="begindate" id="begindate"  style="font-size:1.1em" value="{{$data->begindate ?? $today}}" />
                    
                                            @if ($errors->has('begindate'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('begindate') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 form-group{{ $errors->has('enddate') ? ' has-error' : '' }}">
                                            <label for="enddate">AU(DATE)</label>
                                            <input size="10" type="text" class="form-control apresDate" name="enddate" id="enddate"  style="font-size:1.1em" value="{{$data->enddate ?? $enddate}}" />
                    
                                            @if ($errors->has('enddate'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('enddate') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="padding-top: 25px;">
                                    <div class=" form-group checkbox-inline{{ $errors->has('activated') ? ' has-error' : '' }} bt-df-checkbox">
                                        <label for="activated">
                                            <input id="activated" type="checkbox" class="" name="activated"  value="1" checked="checked"> Activer compte</label>
                    
                                        @if ($errors->has('activated'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('activated') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                                 @isset($data)
                                     <div class="col-sm-4" style="padding-top: 34px;">
                                         <a href="#" id="lienItem" onclick="sendData('v=yes','{{route('ajax.reinitPassword')}}', 'formPasswordItem')">Reinitialiser le Mot de passe</a>
                                     </div>
                                     <div id="formPasswordItem" style="padding-top: 34px;"></div>
                                 @endisset
                                 {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <hr>
        {!! $listeUsers !!}
    </div>

    <script>
        function chercherAnnonceur(secteurID) {
            var url = "{{route ('ajax.listSelectAnnonceur')}}";
            $.ajax({
                type : "POST",
                url : url ,
                dataType: 'JSON',
                data : {
                    secteur : secteurID
                },
            
                success : function(result){
                    $('#annonceurfilteroperation').empty().append('<option value="">Choisir un annonceur</option>');
                
                    $.map( result, function( item ) {
                        $('#annonceurfilteroperation').append('<option value="'+item.id+'">' + item.raisonsociale + '</option>');
                    });
                    $('#annonceurfilteroperation').trigger("chosen:updated");
                
                }
            
            });
        }
    </script>
@endsection
