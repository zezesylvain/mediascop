@extends("layouts.frame")
@section("frameContainer")
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="message" class="alert " style="padding-top: 0;">
        </div>
    </div>
    <style>
        #formInputSaisie label span{
            color: red;
        }
    </style>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @inject("saisie", "\App\Http\Controllers\Administration\SaisieController")
        @inject("form", "\App\Http\Controllers\core\FormController")
        <form method="post" action="" autocomplete="on" id="formInputSaisie">
            {!! csrf_field() !!}
            <input type="hidden" name="mediaUri" value="{{$action}}">
            <input type="hidden" name="campagne" value="{{$campagneID}}">
            <input type="hidden" name="mediaID" value="{{$media}}">
            <input type="hidden" name="campagnetitle" value="{{$campagneTitleID}}">
            <div class="row">
                <div class="container-fluid">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group-inner">
                        <label for="date">Date <span>*</span></label>
                        <input type="text" class="form-control avantDate" name="date" id="date" value="{{date ("Y-m-d")}}"/>
                    </div>
                </div>
            </div>
            <hr class="trait-bleu">
            <div class="row">
                <div class="col-xs-12" id="addinput">
                    @if($media === 7)
                        {!! $saisie->createInputHorsMedia($media,$campagneID) !!}
                    @else
                        {!! $saisie->createInputMedia ($media) !!}
                    @endif
                </div>
                <div class="col-lg-12 col-sm-12 col-xs-12">
                    <hr class="">
                    <button id="btnValider" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="listeDesSaisies" class="">
            @php($userID = \Illuminate\Support\Facades\Auth::id ())
            {!! $saisie->mesSaisies ($media,$userID) !!}
        </div>
    </div>

    <script type="text/javascript">
        $('#formInputSaisie').submit(function (event) {
            event.preventDefault();
            //$('#formInputSaisie #btnValider').attr('disabled','disabled');
            var url = "{{route ('saisie.storePub')}}";
            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#formInputSaisie #btnValider').removeAttr('disabled');
                    $('#message').css('display', 'block').html(data.message).addClass(data.css_alert);
                    $('#listeDesSaisies').html(data.pub_saisie);
                    //$('#formInputSaisie input[name="tarif"]').val('');
                    //$('#formInputSaisie input[name="coeff"]').val('');
                    //$('#formInputSaisie input[type="text"]').val('');
                    $('#formInputSaisie #heure').val('');
                    $('#formInputSaisie #nombre').val('');
                    $('#formInputSaisie #investissement').val('');
                    $('#formInputSaisie #internet_emplacement').val('').trigger("chosen:updated");
                    $('#formInputSaisie #presse_page').val('').trigger("chosen:updated");
                    $('#formInputSaisie #type_promo').val('').trigger("chosen:updated");
                    $('#formInputSaisie #type_service').val('').trigger("chosen:updated");
                    $('#formInputSaisie #ville').val('').trigger("chosen:updated");
                    $('#formInputSaisie #commune').val('').trigger("chosen:updated");
                    $('#date').val(data.today);
                    //$('#listeDesPointsHM').html('');
                        setTimeout(function () {
                            $('#message').css('display', 'none').removeClass(data.css_alert);
                        }, 7000);
                    //document.getElementById(myFrameForm).contentDocument.location.reload(true);
                },
                error: function (xhr, status, errorThrown) {
                    //alert(errorThrown + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    $('#message').css('display', 'block').html("Une erreur <b>"+errorThrown+"</b> est survenue, veuillez recommencer Si l'erreur persiste veuillez contacter votre administrateur technique!").addClass("alert-danger");
                    setTimeout(function () {
                        $('#message').css('display', 'none').removeClass("alert-danger");
                    }, 7000);
                }
            });

        });
    </script>

    <div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"></h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="PrimaryModalhdbgclItem"></div>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" href="#">Cancel</a>
                </div>
            </div>
        </div>
    </div>

@endsection
