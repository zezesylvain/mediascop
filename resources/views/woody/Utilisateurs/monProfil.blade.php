@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Profil Utilisateurs",1,"","user") !!}
    <div class="col-sm-12">
        @inject("profil","\App\Http\Controllers\core\UtilisateursController")
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="box-photo">
                                    {!! $profil->photoUser($userID) !!}
                                </div>
                                <div class="lien-photo">
                                    <a title="Modifier la photo" href="{{route ('user.changerPhotoProfil')}}"><i class="fa fa-pencil"></i></a>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="box-user-info">
                                    <h3>{{$username}}</h3>
                                    <p>
                                        <b>Profil:</b> <i> {{$userProfil}}</i>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($view == 1)
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <hr class="trait-bleu">
                        <form method="post" action="{{route ('user.maPhoto')}}" enctype="multipart/form-data" class="row">
                            {!! csrf_field () !!}
                            <div class="hpanel mg-b-15">
                                <div class="panel-heading hbuilt mailbox-hd">
                                    Importer une photo
                                </div>
                                <div class="panel-body">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="file-upload-inner file-upload-inner-right ts-forms">
                                            <div class="input append-big-btn {{ $errors->has('email') }}">
                                                <label class="icon-left" for="append-big-btn">
                                                    <i class="fa fa-download"></i>
                                                </label>
                                                <div class="file-button">
                                                    Browse
                                                    <input type="file" name="photo" onchange="document.getElementById('append-big-btn').value = this.value;" value="{{ old('photo') }}">
                                                </div>
                                                <input type="text" id="append-big-btn" placeholder="aucun fichier choisi">
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    <div id="validerPhotoItem"></div>
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Valider</button>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            @endif
        </div>

    <div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><i class="fa fa-photo"></i> Charger une photo de profil</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="PrimaryModalhdbgclItem">
                    </div>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" href="#">Fermer</a>
                </div>
            </div>
        </div>
    </div>

@endsection
