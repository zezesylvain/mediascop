@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Historique de connexion",1) !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <form action="{{route ('getHistoriqueDeConnexion')}}" method="post">
                    @csrf
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
                        <label for="profil">Profils</label>
                        <select class="form-control chosen-select" name="profil" id="profil" onchange="getUsersByProfil(this.value)" required>
                            <option value="">[--Choisir un profil--]</option>
                            @foreach ($lesProfils as $row)
                                @php($sm = session ()->has ('historiqueUserVar') && session ()->get ('historiqueUserVar.profil') == $row['id'] ? "selected" : "")
                                <option value="{{$row['id']}}" {{$sm}}>{{$row['name']}}</option>';
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
                        <label for="userProfilItem">Utilisateurs</label>
                        <select class="form-control chosen-select" name="user" id="userProfilItem" required>
                            <option value="">[--Choisir un utilisateur--]</option>
                            @if(count ($users))
                                @foreach ($users as $row)
                                    @php($sm =  session ()->get ('historiqueUserVar.user') == $row['id'] ? "selected" : "")
                                    <option value="{{$row['id']}}" {{$sm}}>{{$row['name']}}</option>';
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <?php
                        $dateDeb = session ()->has ('historiqueUserVar') && !is_null (session ()->get ('historiqueUserVar.date_deb')) ? session ()->get ('historiqueUserVar.date_deb') : $today;
                        $dateFin = session ()->has ('historiqueUserVar') && !is_null (session ()->get ('historiqueUserVar.date_fin')) ? session ()->get ('historiqueUserVar.date_fin') : $today;
                    ?>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
                        <label for="date_deb">Date debut</label>
                        <input type="text" class="form-control avantDate" name="date_deb" id="date_deb" value="{{$dateDeb}}">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
                        <label for="date_fin">Date fin</label>
                        <input type="text" class="form-control avantDate" name="date_fin" id="date_fin" value="{{$dateFin}}">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Valider</button>
                    </div>
                </form>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr>
                    {!! $historiqueDeConnexion !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        function getUsersByProfil(profilID) {
            var url = "{{route ('ajax.getUsersByProfil')}}";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    profilID: profilID
                },
                dataType: "JSON",
                success: function (data) {
                    var opts = data.users;

                    $('#userProfilItem').empty().append('<option value="">[-Choisir un utilisateur-]</option>');
                    $.map( opts, function( item ) {
                        $('#userProfilItem').append('<option value="'+item.id+'">' + item.name + '</option>');
                    });
                    $('#userProfilItem').trigger("chosen:updated");
                }
            });
        }

    </script>
@endsection
