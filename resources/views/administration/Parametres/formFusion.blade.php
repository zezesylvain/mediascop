@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Faire les fusions") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label for="table">Tables</label>
                        <select name="table" id="table" class="form-control chosen-select" onchange="javascript:location.href=this.value">
                            <option value="">Choisir une table</option>
                            @foreach($listeTablesFusions as $r)
                                @php($selected = $r['id'] == session ()->get ('tableFusionCheckDonnee') ? "selected" : "")
                                <option value="{{route ('parametre.getDonneesFusion',$r['id'])}}"  {{$selected}}>{{$r['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                {!! $tableauDeDonnee !!}
                
            </div>
        </div>
    </div>
@endsection
