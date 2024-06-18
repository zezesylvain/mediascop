<tr>
    <td>{{$rowID}}</td>
    @if(!$rowvalide)
        <td>{!!$rowdate!!}</td>
    @else
        <td>{!!$date2Fr ($rowdate)!!}</td>
    @endif
    @if($action !== "HORS-MEDIAJJ")
        {!! $tdDuree !!}
    @endif
    @if($action === "TELEVISION" || $action === "RADIO")
        {!! $tdOperation !!}
        {!! $tdHeure !!}
    @endif
    @if($action === "PRESSE" || $action === "INTERNET")
        {!! $tdEmplacement !!}
    @endif
    @if($action === "AFFICHAGE")
        {!! $tdAffichage !!}
    @endif
    @if(!$rowvalide && $action !== 'AFFICHAGE')
        <td>{!!$supportID!!}</td>
    @elseif($action !== 'AFFICHAGE')
        <td>{!!$rowsupport!!}</td>
    @endif
    <td>{!! $campagnetitle !!}</td>
    {!! $tdHorsMedia !!}
    @if($action !== "HORS-MEDIA")
        <td>{!! $rowtarif !!}</td>
        <td>{!! $rowcoeff !!}</td>
    @endif
    @if(!$rowvalide)
        <td>{!! $userSaisie !!}</td>
    @endif
    {!! $tdErreur !!}
    @if(!$rowvalide)
        <td>
             <div id="validebox{{$rowID}}">
                 @if($checkErr === 'checked')
                     <a href="{{ route('valider.supprPubErreur',[$rowID]) }}" title="Supprimer la pub"><i class="fa fa-trash"></i></a>
                 @else
                     <input id="listpub[{{$ij}}]" type="checkbox" name="listpub[]" value="{{$rowID}}"><br>
                     <a href="#plisting" title="Valider la pub" onclick="sendData('database={{$DATABASE}}&id={{$rowID}}', '{{$routeval}}', 'validebox{{$rowID}}');" style="color: #0000cc;">
                         <i class="fa fa-check-circle"></i>
                     </a>
                 @endif
             </div>
        {{--@else
            @include("administration.TableauDeBords.checkedPubs")--}}
        </td>
    @endif

    @if($rowvalide)
        @if($action === 'AFFICHAGE')
            <td>
                <a href="#plisting" title="Modifier la saisie!" data-toggle="modal" data-target="#ModalUpdateAffichage" onclick="getFormUpdateAffichage('{{$rowID}}')">
                    <i class="fa fa-pencil"></i>
                </a>
            </td>
        @endif
    @endif

</tr>

@if($action === 'AFFICHAGE' && $rowvalide === 1)
    <div id="ModalUpdateAffichage" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Modifier Saisie Affichage</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('valider.updateAffichage') }}" method="POST" id="formAffichageUpdateZZ">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="dateDebut">Date Debut</label>
                                <input type="text" class="form-control avantDate" name="dateDebut" id="dateDebut" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="dateFin">Date Fin</label>
                                <input type="text" class="form-control avantDate" name="dateFin" id="dateFin" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="investissement">Investissement</label>
                                <input type="number" class="form-control" name="investissement" id="investissement" min="0" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="nombre">Nombre</label>
                                <input type="number" class="form-control" name="nombre" id="nombre" min="0" required>
                            </div>
                        </div>

                        <input type="hidden" name="uniqID" id="uniqID">
                        <input type="hidden" name="pid" id="pID">

                        <div class="row">
                            <div class="col-lg-3">
                                <br>
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </div>
                    </form>
                    <div id="PrimaryModalhdbgclItem"></div>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" href="#">Fermer</a>
                </div>
            </div>
        </div>
    </div>

@endif

<script>
    function signalerErreur(pubID,etat,ij) {
        var url = "{{route ('ajax.signalerErreurPub')}}";
        $.ajax({
            type : "POST",
            url : url ,
            data : {
                pubID: pubID,
                etat: etat,
                ij: ij,
            },
            dataType: 'json',
            async: true,
            success : function(result)
            {
                if (result.err === 1)
                {
                    $('#validebox'+pubID+'').html('<a href="'+result.uriDel+'" title="Supprimer la pub"><i class="fa fa-trash"></i></a>');
                }
                if (result.err === 0)
                {
                    $('#validebox'+pubID+'').html(result.htm);
                }
            }
        });
    }

    function getFormUpdateAffichage(pid) {
        var url = "{{ route('ajax.getFormUpdateAffichage') }}";
        var dateDeb = 'dateDebut';
        var dateFin = 'dateFin';
        $.ajax({
            type: "POST",
            url: url,
            data: {
                pid : pid
            },
            dataType: "json",
            async: true,
            success : function (result) {
                console.log(result,dateFin,dateDeb);
                setfpdate(dateDeb,result.dateDeb);
                setfpdate(dateFin,result.dateFin);
                $('#investissement').val(result.investissement);
                $('#nombre').val(result.nombre);
                $('#uniqID').val(result.uniq_id);
                $('#pID').val(result.pid);
            }
        });
    }

</script>

<script>
    $(function () {
       $('#formAffichageUpdate').on('submit', function (e) {
           e.preventDefault();
           $.ajax({
               type: 'post',
               url: '{{ route('valider.updateAffichage') }}',
               data: $('#formAffichageUpdate').serialize(),
               dataType: "json",
               success: function (result) {

               }
           })
       })
    })
</script>
