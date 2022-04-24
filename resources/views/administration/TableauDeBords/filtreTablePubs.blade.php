<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <hr class="trait-bleu">
    <h4>Filtre sur la table</h4>
    @php
        $tAnnonceur = $dbTable ('DBTBL_ANNONCEURS','db');
        $tSupport = $dbTable ('DBTBL_SUPPORTS','db');
        $tFormat = $dbTable ('DBTBL_FORMATS','db');
        $tNature = $dbTable ('DBTBL_NATURES','db');
    @endphp
    <div class="row">
        <form action="{{route ('dashbord.formExportQuery')}}" method="post" id="formTablePubs">
            {!! csrf_field () !!}
            <input type="hidden" name="table" value="{{$table}}">
            <div class="col-sm-3 form-group">
                <label for="date_deb">Date debut</label>
                <input name="date_debut" id="date_deb" class="form-control avantDate" >
            </div>
            <div class="col-sm-3 form-group">
                <label for="date_fin">Date fin</label>
                <input name="date_fin" id="date_fin" class="form-control avantDate" >
            </div>
            <div class="col-sm-3 form-group">
                <label for="secteur">Secteur</label>
                <select name="secteur" id="secteur" class="form-control chosen-select" onchange="donneesDeTable(this.value,'{{$tAnnonceur}}','secteur','annonceurItem','un annonceur')">
                    <option value="">Choisir un secteur</option>
                    @foreach($secteurs as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="annonceurItem">Annonceur</label>
                <select name="annonceur" id="annonceurItem" class="form-control chosen-select">
                    <option value="">Choisir un annonceur</option>
                    @foreach($annonceurs as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="media">Média</label>
                <select name="media" id="media" class="form-control chosen-select" onchange="donneesDeTable(this.value,'{{$tSupport}}','media','support','un support');donneesDeTable(this.value,'{{$tFormat}}','media','format','un format');donneesDeTable(this.value,'{{$tNature}}','media','nature','une nature')">
                    <option value="">Choisir un média</option>
                    @foreach($medias as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="support">Support</label>
                <select name="support" id="support" class="form-control chosen-select">
                    <option value="">Choisir un support</option>
                    @foreach($supports as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="format">Format</label>
                <select name="format" id="format" class="form-control chosen-select">
                    <option value="">Choisir un format</option>
                    @foreach($formats as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="nature">Nature</label>
                <select name="nature" id="nature" class="form-control chosen-select">
                    <option value="">Choisir une nature</option>
                    @foreach($natures as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="couverture">Couverture</label>
                <select name="couverture" id="couverture" class="form-control chosen-select">
                    <option value="">Choisir une couverture</option>
                    @foreach($couvertures as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="cible">Cible</label>
                <select name="cible" id="cible" class="form-control chosen-select">
                    <option value="">Choisir une cible</option>
                    @foreach($cibles as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12"></div>
            <div class="col-sm-3" style="padding-top: 25px;">
                <button class="btn btn-primary btn-block" type="submit">Filtrer</button>
            </div>
        </form>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="resultatItem"></div>
</div>

<script>
       function donneesDeTable(fk,table,champs,item,texte,orderByChamps='name'){
           var url = "{{route ('ajax.getDonneesDeTable')}}";
           var select_elem = $('#'+item+'');
           $.ajax({
               type : "POST",
               url : url ,
               data : {
                   fk : fk,
                   champs : champs,
                   table : table,
                   texte : texte,
                   orderByChamps : orderByChamps,
               },
               dataType: "JSON",
               success : function(result){
                   select_elem.empty().append('<option value="">Choisir '+result.selectText+'</option>');
                   $.map( result.donnees, function( item ) {
                       select_elem.append('<option value="'+item.id+'">' + item.name + '</option>');
                   });
                   select_elem.trigger("chosen:updated");

               }

           });
       }


       $(document).ready(function () {
           $('#formTablePubs').submit(function (event) {
               event.preventDefault();
               var url = "{{route('ajax.getDonneesPubs')}}";
               $.ajax({
                   url: url,
                   method: 'POST',
                   data: new FormData(this),
                   dataType: "JSON",
                   contentType: false,
                   cache: false,
                   processData: false,
                   success: function (data) {
                       $('#resultatItem').html(data.link);
                   }
               });
           })
       })

</script>
