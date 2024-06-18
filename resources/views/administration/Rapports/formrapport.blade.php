@extends("layouts.admin")
@section("container")
    @inject('rapp','App\Http\Controllers\Administration\RapportController')
    <style>
        .f-rapport .chosen-container{
            position: relative!important;
            top: -15px!important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2>Gestion des visuels <a href="" title="Charger les visuels"><i class="fa
            fa-refresh"></i></a></h2>
            <hr>
        </div>
        <div class="col-lg-3">
            <a href="{{route('reporting.rapport')}}" class="btn btn-primary btn-block">Retourner aux rapports</a>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="messageRapportValider" class="alert"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="mediainSevernotinbd">
            <hr class="trait-bleu">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div id="mediasRapportsItem">
                        {!! $rapp->makeFormVisuelRapport() !!}
                        {{--{!! $rapp->makeListeRapportsCharges() !!}--}}
                    </div>
                </div>
            </div>
        </div>
        @php($diruser = "rapports")
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="dropzone-pro">
                <div id="dropzone">
                    <form action="{{route ('saisie.uploadFiles')}}" class="dropzone dropzone-custom needsclick" id="demo-upload">
                        {!! csrf_field () !!}
                        <input type="hidden" name="uploadDir" value="{{$uploadDir ?? ""}}">
                        <div class="dz-message needsclick download-custom">
                            <i class="fa fa-cloud-download" aria-hidden="true"></i>
                            <h2>Déposer les fichiers ici ou cliquer pour charger.</h2>
                            <p><span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function chargerVisuelsRapport() {
            var url = "{{route ('ajax.getFormRapport')}}";
            var ok = true;
            $.ajax({
                type : "POST",
                url : url ,
                dataType: 'JSON',
                data : {
                    action : ok
                },
        
                success : function(result){
                    $('#mediasRapportsItem').html(result.formRapport);
                }
            });
        }
        function deleteVisuelsRapport(fichier,idForm) {
            var url = "{{route ('ajax.delfile')}}";
            $.ajax({
                type : "POST",
                url : url ,
                dataType: 'JSON',
                data : {
                    file : fichier,
                    idForm : idForm
                },
        
                success : function(data){
                    $('#messageRapportValider').css('display', 'block').html(data.message).addClass(data.alert);
                    if (data.valider === true){
                        $('#tab-'+data.idForm).css('display','none');
                    }
                    setTimeout(function () {
                        $('#messageRapportValider').css('display', 'none');
                    },4000);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $('.uploadForm').click(function (event) {
                event.preventDefault();
                var url = "{{route('rapport.validerRapport')}}";
                var form = $(this).closest('form')
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: new FormData(this),
                    //data: form.serialize(),
                    dataType: "JSON",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#messageRapportValider').css('display', 'block').html(data.message).addClass(data.alert);
                        if (data.valider === true){
                            $('#tab-'+data.idForm).css('display','none');
                        }
                       
                        setTimeout(function () {
                            $('#messageRapportValider').css('display', 'none');
                        },4000);
                    },
                    error:function (err) {
                        console.log(err);
                    }
                });
            });
        })

    </script>

    <script>
        function getFormValiderRapport() {
            
        }
    </script>
@endsection
