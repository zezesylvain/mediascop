@extends('layouts.admin')
@section('container')
    {!! $titreHtml("ABONNEMENTS CLIENTS",1) !!}
    @inject("admin", "App\Http\Controllers\Client\AdminController")
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                   data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" class="table table-responsive table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>NOM et PR&Eacute;NOMS</th>
                                    <th>PROFIL</th>
                                    <th>SECTEUR D'ACTIVITE</th>
                                    <th>ENTREPRISE</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i = 0)
                                @foreach($utilisateurs as $k => $r)
                                    @php($i++)
                                    <tr>
                                        <td>{!! $i !!}</td>
                                        <td>{{ $r['name'] }}</td>
                                        <td>{{ $getChampTable($tProfil,$r['profil']) }}</td>
                                        <td>
                                            <span id="userSecteur-{{$r['id']}}">
                                                {{ $admin->getUserSecteur($r['id'])}}
                                            </span>
                                        </td>
                                        <td>
                                           <span id="userSociete-{{$r['id']}}">
                                                {{ $admin->getUserSociete($r['id']) }}
                                           </span>
                                        </td>
                                        <td>
                                            <a href="#user{{$i}}" title="Faire un abonnement!" data-toggle="modal" data-target="#MyModalAlert" onclick="formAbonnements('{{$r['id']}}')">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="MyModalAlert" class="modal modal-adminpro-general FullColor-popup-DangerModal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h2>Faire Abonnement de <span id="userAbonnementItem"></span></h2>
                </div>
                <div class="modal-body" style="padding-top: 5px!important;">
                    @include("administration.Utilisateurs.formAbonnements")
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" href="#">Fermer</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formAbonnements(userID) {
            var url = "{{route ('ajax.chercherFormAbonnement')}}";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    userID: userID
                },
                dataType: "JSON",
                success: function (data) {
                    $("#userAbonnementItem").text(data.nomUtilisateur).css('color','#ff0000');
                    
                    $('#formAbonnerSecteur #secteur').empty().append('<option value="">Choisir un secteur</option>');
                    var userSecteur = data.formAbonnementSecteur.uSecteur;
                    $.map( data.formAbonnementSecteur.secteurs, function( item ) {
                        $('#formAbonnerSecteur #secteur').append('<option value="'+item.id+'">' + item.name +
                            '</option>');
                    });
    
                    $('#formAbonnerSecteur #secteur').val(userSecteur).trigger("chosen:updated");
                    $('#formAbonnerSecteur #sectorUserID').val(data.formAbonnementSecteur.userID);
                    if (data.formAbonnementSecteur.userSecteur !== '-'){
                        $('#formAbonnerSecteur #sectorPID').val(data.formAbonnementSecteur.pid);
                    }
                    
                    $('#formAbonnerSociete #annonceur').empty().append('<option value="">Choisir un annonceur</option>');
                    $.map( data.formAbonnementSociete.annonceurs, function( item ) {
                        $('#formAbonnerSociete #annonceur').append('<option value="'+item.id+'">' + item.name + 
                            '</option>');
                    });
                    var userAnnonceur = data.formAbonnementSociete.uAnnonceur;
                    $('#formAbonnerSociete #annonceur').val(userAnnonceur).trigger("chosen:updated");
                    
                    $('#formAbonnerSociete #societeUserID').val(data.formAbonnementSociete.userID);
                    if (data.formAbonnementSociete.userAnnonceur !== '-'){
                        $('#formAbonnerSociete #societePID').val(data.formAbonnementSociete.pid);
                    }
                }
            });
        }
    </script>
@endsection
