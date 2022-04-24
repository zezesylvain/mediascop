<form method="post" action="{{route ('saisie.storeCampagne')}}">
    {{csrf_field()}}
    <input type="hidden" value="{{$idcamptitle}}" name="campagnetitleID">
    <div class="row">
        <div class="col-xs-6 col-sm-3 col-lg-2">
            <div class="chosen-select-single mg-b-20 form-group-inner">
                <label for="media"> M&eacute;dia</label>
                <select data-placeholder="Choisir un media" class="chosen-select form-control" tabindex="-1"
                        name="media" id="media" onchange="inputFormCampagne(this
                    .value,'{{$idcamptitle}}')">
                    <option value="0"></option>
                    @foreach($listeDesMedias as $rows)
                        <option value="{{$rows['id']}}">{{$rows['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
            <label for="format"> Format</label>
            <select class="form-control chosen-select" name="format" id="format" data-placeholder="Choisir un format">
                <option value="">Choisir un format</option>
            </select>
        </div>
    
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
            <label for="nature"> Nature </label>
            <select class="chosen-select form-control" name="nature" id="nature">
                <option value="">Choisir une nature</option>
            </select>
        </div>
    
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
            <label for="cible"> Cible </label>
            <select class="chosen-select form-control" name="cible" id="cible">
                <option value="">Choisir une cible</option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
            <label for="type_promo"> Type de Promo </label>
            <select class="chosen-select form-control" name="type_promo" id="type_promo">
                <option value="">Choisir un type de promo</option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
            <label for="type_service"> Type de Service </label>
            <select class="chosen-select form-control" name="type_service" id="type_service">
                <option value="">Choisir un type de service</option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner" id="box_internet_dimension" style="display: none">
            <label for="internet_dimension">Dimension </label>
            <select class="chosen-select form-control" name="" id="internet_dimension">
                <option value="">Choisir une dimension</option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner" id="box_presse_calibre" style="display: none;">
            <label for="presse_calibre"> Calibre </label>
            <select class="chosen-select form-control" id="presse_calibre" name="">
                <option value="">Choisir un calibre</option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner" id="box_presse_couleur" style="display: none">
            <label for="presse_couleur"> Couleur </label>
            <select class="chosen-select form-control" id="presse_couleur" name="">
                <option value="">Choisir une couleur</option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner" id="box_affichage_dimension">
            <label for="affichage_dimension"> Dimension </label>
            <select class="chosen-select form-control" id="affichage_dimension" name="">
                <option value="">Choisir une dimension</option>
            </select>
        </div>
        <div id="formatitem"></div>
        <div id="specificitem"></div>
    </div>
    <div id="btnValider"></div>
</form>

<script>
    function inputFormCampagne(mediaID,campTitleID) {
        var url = "{{route ('ajax.formInputCampagne')}}";
        $.ajax({
            type : "POST",
            url : url ,
            dataType: 'JSON',
            data : {
                mediaID : mediaID,
                campTitleID : campTitleID
            },
        
            success : function(data){
                remplirChampSelect('format',data.formats,'name','Choisir un format');
                remplirChampSelect('nature',data.natures,'name','Choisir une nature');
                remplirChampSelect('cible',data.cibles,'name','Choisir une cible');
                remplirChampSelect('type_promo',data.typePromos,'name','Choisir un type de Promo');
                remplirChampSelect('type_service',data.typeServices,'name','Choisir un type de Service');
                if (data.autreChpSelect){
                    if (data.media === 3){
                       $('#box_presse_calibre').css('display','block');
                       $('#presse_calibre').attr('name','presse_calibre');
                        remplirChampSelect('presse_calibre',data.pcalibre,'name','Choisir un calibre');
                       $('#box_presse_couleur').css('display','block');
                       $('#presse_couleur').attr('name','presse_couleur');
                        remplirChampSelect('presse_couleur',data.pcouleur,'name','Choisir une couleur');
                    }else {
                        $('#box_presse_calibre').css('display','none');
                        $('#presse_calibre').attr('name','');
                        $('#box_presse_couleur').css('display','none');
                        $('#presse_couleur').attr('name','');
                    }
                    if (data.media == 4){
                        $('#box_internet_dimension').css('display','block');
                        $('#internet_dimension').attr('name','internet_dimension');
                        remplirChampSelect('internet_dimension',data.listeDesDimensions,'name','Choisir une dimension');
                    }else {
                        $('#box_internet_dimension').css('display','none');
                        $('#internet_dimension').attr('name','');
                    }
                    if (data.media == 6){
                        $('#box_affichage_dimension').css('display','block');
                        $('#affichage_dimension').attr('name','affichage_dimension');
                        remplirChampSelect('affichage_dimension',data.affichageDimensions,'name','Choisir une dimension');
                    }else {
                        $('#box_affichage_dimension').css('display','none');
                        $('#affichage_dimension').attr('name','');
                    }
                }else {
                    $('#box_presse_calibre').css('display','none');
                    $('#presse_calibre').attr('name','');
                    $('#box_presse_couleur').css('display','none');
                    $('#presse_couleur').attr('name','');
                    $('#box_internet_dimension').css('display','none');
                    $('#internet_dimension').attr('name','');
                    $('#box_affichage_dimension').css('display','none');
                    $('#affichage_dimension').attr('name','');
                }
                $('#specificitem').html(data.form);
                $('#btnValider').html(data.btn);
            }
        });
    }
    
    function remplirChampSelect(identifiant,datas,prop,placeholder) {
        $('#'+identifiant+'').empty().append("<option>"+placeholder+"</option>");
        $.map( datas, function( item) {
            $('#'+identifiant+'').append("<option value="+item.id+">"+item.name+ "</option>");
        });
        $('#'+identifiant+'').trigger("chosen:updated");
    }
</script>
