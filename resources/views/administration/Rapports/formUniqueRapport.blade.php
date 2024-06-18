<div class="f-rapport" id="tab-{{$indic}}">
    <form id="essai{{$indic}}" name="essai" class="formRapport" method="post" action="{{route("rapport.validerRapport")}}" novalidate
          autocomplete="on">
        <input type="hidden" value="OK" name="formok">
        <input type="hidden" value="{{$indic}}" name="id-form">
        <input type="hidden" name="file{{$indic}}" value="{{$newbasename}}">
        {{csrf_field()}}
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 form-group">
                <label for="secteur{{$indic}}" accesskey="s">Secteur d'activit&eacute;</label>
                <select class="form-control chosen-select" name="secteur{{$indic}}" id="secteur{{$indic}}" required>
                    <option value="">[--Choisir un secteur--]</option>
                    @foreach ($secteurs as $rows)
                        <option value="{{$rows['id']}}">{{$rows['name']}}</option>
                    @endforeach
                </select>
                @if ($errors->has('secteur'.$indic))
                    <span class="help-block">
                        <strong>{{ $errors->first('secteur'.$indic) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 form-group">
                <label for="title{{$indic}}"> Titre</label>
                <input class="form-control" type="text" name="title{{$indic}}" id="title{{$indic}}" value="{{$texte}}">
                @if ($errors->has('title'.$indic))
                    <span class="help-block">
                        <strong>{{ $errors->first('title'.$indic) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12  form-group">
                <label for="motcles{{$indic}}">Mots cl&eacute;s s&eacute;par&eacute;s par une virgule</label>
                <input class="form-control" type="text" id="motcles{{$indic}}" name="motcles{{$indic}}" value="{{$motcles}}" placeholder="Mot1, mot 2, mot 3, ...">
                @if ($errors->has('motcles'.$indic))
                    <span class="help-block">
                        <strong>{{ $errors->first('motcles') }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 form-group">
                <label for="periodicite{{$indic}}">P&eacute;riodicit&eacute;</label>
                <select class="form-control chosen-select" name="periodicite{{$indic}}" id="periodicite{{$indic}}">
                    @foreach ($periodicite as $rowp) :
                    <option value="{{$rowp['id']}}">{{$rowp['name']}}</option>
                    @endforeach
                </select>
                @if ($errors->has('periodicite'.$indic))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="beginDate{{$indic}}">DU :</label>
                        <input type="text" name="begindate{{$indic}}" id="beginDate{{$indic}}" class="form-control avantDate" value="{{date('Y-m-d')}}" required >
                        @if ($errors->has('begindate'.$indic))
                            <span class="help-block">
                                <strong>{{ $errors->first('begindate'.$indic) }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-xs-6">
                        <label for="endDate{{$indic}}">AU :</label>
                        <input type="text" name="enddate{{$indic}}" id="endDate{{$indic}}" class="form-control avantDate" value="{{date('Y-m-d')}}" required >
                        @if ($errors->has('enddate'.$indic))
                            <span class="help-block">
                                <strong>{{ $errors->first('enddate'.$indic) }}</strong>
                            </span>
                        @endif
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
                <button type="submit"  class="btn btn-primary btn-block">Ajouter</button>
            </div>
        </div>
    </form>
    <hr class="trait-bleu">
</div>
