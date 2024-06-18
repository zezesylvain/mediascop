<div class="row" id="campagnefilter"> </div>
<hr class="trait-bleu">
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-sm-9 form-group">
                <label for="title"> Titre (250 caractères maximum)</label>
                <input class="form-control" type="text" id="title" onchange="validerTitreCampagneItem(this.value,'{{$operationID}}')" name="title" value="" required maxlength="250"/>
            </div>
            <div class="col-sm-3 form-group">
                <label for="type_promo">Type Promo</label>
                <select name="type_promo" id="type_promo" class="form-control chosen-select">
                    <option value="">Faire un choix</option>
                </select>
            </div>
            <div class="col-sm-6" style="padding-top: 10px;">
                <button class="btn btn-primary" type="submit" id="btnValiderCampTitle" disabled >Valider</button>
            </div>
        </div>
    </div>
</div>

<script>
    function validerTitreCampagneItem(titre,opid) {
        $.ajax({
            method: "POST",
            url: "{{ route ('ajax.validerTitreCampagne') }}",
            data:{
                opid: opid,
                title: titre
                 },
            dataType: "JSON",
            cache: false,
            processData: true,
            async: false,
            /*beforeSend: function () {
                $('#opitem').html("<img src='{{asset('medias/loader.png')}}'/>");
            }*/
        }).done(function (response) {
            if (response.validerOffre === true){
                $('#btnValiderCampTitle').attr('disabled',false);
                $('#title').css({
                    'border' : '2px solid green'
                })
            }else {
                $('#btnValiderCampTitle').attr('disabled',true);
                $('#title').css({
                    'border' : '2px solid red'
                })
            }
        });

    }
</script>