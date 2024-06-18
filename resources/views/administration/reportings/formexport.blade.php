@extends("layouts.admin")
@section("container")
    <div class="blog-area mg-tb-15">
        <div class="container-fluid">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>Exporter les données</h3>
                <hr>
            </div>
            <div class="sparkline16-list responsive-mg-b-30 def-form">
                <div class="sparkline16-graph">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form class="form-group-inner" id="essai" name="essai" method="post" action="{{route('dashbord.exportData')}}" autocomplete="on">
                                    @csrf
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-sm-4 col-md-4 col-lg-3 form-group">
                                                    <label for="dateDeb">Date debut</label>
                                                    <input type="date" class="form-control avantDate" name="date_debut" id="dateDeb" value="{{ $date_debut }}" required>
                                                </div>
                                                <div class="col-sm-4 col-md-4 col-lg-3 form-group">
                                                    <label for="dateFin">Date fin</label>
                                                    <input type="date" class="form-control avantDate" name="date_fin" id="dateFin" value="{{ $date_fin }}" required>
                                                </div>
                                            </div>
                                            <div id="secteuritem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                                <div class="checkbox">
                                                    <label for="checkall" class="checkbox-inline">
                                                        <input name="checkall" type="checkbox" checked="checked" value="1" id="checkall"> Tout exporter directement
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="secteuritem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="secteur"> Secteur</label>
                                        <select class="form-control chosen-select " name="secteur" id="secteur" onChange="filtreDonnees('secteur',this.value,'annonceurListItem','annonceur')">
                                            <option value="">Choisir un secteur</option>
                                            @foreach($secteurs as $secteur)
                                                <option value="{{ $secteur['id'] }}">{{ $secteur['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="annonceuritem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="annonceurListItem"> Annonceur</label>
                                        <select class="form-control chosen-select " name="annonceur" id="annonceurListItem">
                                            <option value="">Choisir un annonceur</option>
                                            @foreach($annonceurs as $annonceur)
                                                <option value="{{ $annonceur['id'] }}">{{ $annonceur['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="mediaitem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="media"> M&eacute;dia</label>
                                        <select class="form-control chosen-select " name="media" id="media" onChange="filtreDonnees('media',this.value,'support','support');filtreDonnees('media',this.value,'format','format')">
                                            <option value="">Choisir un média</option>
                                            @foreach($medias as $media)
                                                <option value="{{ $media['id'] }}">{{ $media['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="supportitem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="support"> Support</label>
                                        <select class="form-control chosen-select " name="support" id="support">
                                            <option value="">Choisir un support</option>
                                            @foreach($supports as $support)
                                                <option value="{{ $support['id'] }}">{{ $support['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="formatitem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="format"> Format</label>
                                        <select class="form-control chosen-select " name="format" id="format">
                                            <option value="">Choisir un format</option>
                                            @foreach($formats as $format)
                                                <option value="{{ $format['id'] }}">{{ $format['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="nature"> Nature </label>
                                        <select class="form-control chosen-select " name="nature" id="nature" onchange="sendData('nature=' + this.value, 'modules/export/parrain.php', 'parrainitem');">
                                            <option value="">Choisir une nature</option>
                                            @foreach($natures as $nature)
                                                <option value="{{ $nature['id'] }}">{{ $nature['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="couvertureitem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="couverture"> Couverture </label>
                                        <select class="form-control chosen-select " name="couverture" id="couverture">
                                            <option value="">Choisir une couverture</option>
                                            @foreach($couvertures as $couverture)
                                                <option value="{{ $couverture['id'] }}">{{ $couverture['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="cibleitem" class="col-sm-6 col-md-4 col-lg-3 form-group">
                                        <label for="cible"> Cible</label>
                                        <select class="form-control chosen-select " name="cible" id="cible">
                                            <option value="">Choisir une cible</option>
                                            @foreach($cibles as $cible)
                                                <option value="{{ $cible['id'] }}">{{ $cible['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-success btn-block">Valider</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $html !!}
        </div>
    </div>
    <div class="row">
        @includeIf("administration.reportings.listepub")
    </div>

    <script>
        function filtreDonnees(table,id,iditem,cible) {
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.filtreDonnees') }}',
                data: {
                    'laTable': table,
                    'iditem' : iditem,
                    'id' : id,
                    'cible' : cible
                },
                dataType: 'JSON',
                async: false
            }).done(function (data) {
                $('#'+iditem+'').empty().append('<option value="">'+data.optvalue+'</option>');
                $.map( data.result, function(item) {
                    //console.log(item.id,item.name);
                    $('#'+iditem+'').append('<option value="'+item.id+'">' + item.name + '</option>');
                });
                $('#'+iditem+'').trigger("chosen:updated");
            })
        }

    </script>
@stop