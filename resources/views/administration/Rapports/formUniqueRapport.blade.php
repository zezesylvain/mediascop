<div class="f-rapport" id="tab-{{$indic}}">
    <form id="essai" name="essai" class="formRapport" method="post" action="{{route("rapport.validerRapport")}}"
          autocomplete="on">
        <input type="hidden" value="OK" name="formok">
        <input type="hidden" value="{{$indic}}" name="id-form">
        <input type="hidden" name="file" value="{{$newbasename}}">
        {{csrf_field()}}
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                <label for="secteur" accesskey="s">Secteur d'activit&eacute;</label>
                <select class="form-control chosen-select" name="secteur" id="secteur">
                    <option value="">[--Choisir un secteur--]</option>
                    @foreach ($secteurs as $rows)
                        <option value="{{$rows['id']}}">{{$rows['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 form-group-inner">
                <label for="title"> Titre</label>
                <input class="form-control" type="text" name="title" id="title" value="{{$texte}}">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12  form-group-inner">
                <label for="motcles">Mots cl&eacute;s s&eacute;par&eacute;s par une virgule</label>
                <input class="form-control" type="text" id="motcles" name="motcles" value="{{$motcles}}" placeholder="Mot1, mot 2, mot 3, ...">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 form-group-inner">
                <label for="periodicite">P&eacute;riodicit&eacute;</label>
                <select class="form-control chosen-select" name="periodicite" id="periodicite">
                    @foreach ($periodicite as $rowp) :
                    <option value="{{$rowp['id']}}">{{$rowp['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="beginDate">DU :</label>
                        <input type="text" name="begindate{{$indic}}" id="beginDate" class="form-control datepickerjs"
                               value="{{date('Y-m-d')}}">
                    </div>
                    <div class="col-xs-6">
                        <label for="endDate">AU :</label>
                        <input type="text" name="enddate{{$indic}}" id="endDate" class="form-control datepickerjs" value="{{date
                        ('Y-m-d')}}" >
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6" style="padding-top: 30px;">
                <a id="urldel-{{$indic}}" title="Supprimer ce fichier" href="#tab-{{$indic}}"
                   onclick="deleteVisuelsRapport('{{$coolnamebis}}','{{$indic}}')">
                    <i class="fa fa-trash-o fa-2x rouge" style="color: red;"></i>
                </a>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 5px;">
                <button type="submit" class="btn btn-primary btn-block">Ajouter</button>
            </div>
        </div>
    </form>
    <hr class="trait-bleu">
</div>
