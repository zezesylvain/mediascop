@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Exportation de données") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label for="table">Tables</label>
                        <select name="table" id="table" class="form-control chosen-select" onchange="javascript:location.href=this.value">
                            <option value="">Choisir une table</option>
                            @foreach($listeTables as $r)
                                @php($selected = $r['id'] == session ()->get ('exportTable') ? "selected" : "")
                                <option value="{{route ('dashbord.exportTable',$r['id'])}}"  {{$selected}}>{{$r['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                 {!! $filtreTable !!}
                @if(!empty($tableauDeDonnees))
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr class="trait-bleu">
                        {!! $tableauDeDonnees !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
