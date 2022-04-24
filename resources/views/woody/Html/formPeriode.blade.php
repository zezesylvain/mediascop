@extends("layouts.admin")
@section("container")
    <div class="col-sm-4 form-group">
        <label for="periode">Trimeste ou Semestre</label>
        <select name="classe" class="form-control" onchange="sendData('var='+this.value+'&table=tbl&sessionname=periode','{{route('ajax.changeSessionValue')}}','periodeItem')" id="periode">
            <option value="">Choisir un Trimestre(Semestre)</option>
            @foreach($periodes as $periode)
                <?php
                $per = session()->has("periode") && session("periode") ? session("periode") : 0;
                $selected = $periode['id'] == $per ? " selected=\"selected\"" : "";
                ?>
                <option value="{{$periode['id']}}" {{$selected}}>{{$periode['name']}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-4"><div id="periodeItem"></div></div>
    <div class="col-sm-12 form-group">
        <a href="{{url($uri)}}" class="btn  btn-success"><i class="fa fa-arrow-circle-o-right "></i> Suivant</a>
    </div>
@endsection