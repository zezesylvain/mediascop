@extends('layouts.admin')
@section('container')
    @inject("saisie","App\Http\Controllers\Administration\SaisieController")
    @inject("campagne","App\Http\Controllers\Administration\CampagnesController")
    {!! $titreHtml("Campagnes") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! $campagne->makeFormFilterCampagne ($debutop,$ddfinop,$secteurfilteroperation,
                    $annonceurfilteroperation,$action) !!}
                </div>
                {!! $saisie->menuDeModificationDesSaisies () !!}
                <form method="post" action="{{route('saisie.storeCampTitle')}}" autocomplete="on">
                    {!! csrf_field() !!}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! $listeDesOperations !!}
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="opitem"></div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="btnitem"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function getFormSaisieCampTitle(operationID,secteurID,annonceurID)
        {
            $.ajax({
                method: "POST",
                url: "{{ route ('ajax.campagne') }}",
                data:{
                    opid: operationID,
                    secteurID: secteurID,
                    annonceurID: annonceurID },
                dataType: "JSON",
                cache: false,
                processData: true,
                async: false,
                beforeSend: function () {
                    $('#opitem').html("<img src='{{asset('medias/loader.png')}}'/>");
                }
            }).done(function (response) {
                $('#opitem').html(response.formCampTitle);

                $('#type_promo').empty().append("<option value=''>Faire un choix</option>");
                $.map( response.typePromos, function( item) {
                    $('#type_promo').append("<option value="+item.id+">"+item.name+ "</option>");
                });
                $('#type_promo').trigger("chosen:updated");

            });
        }
    </script>
@endsection
