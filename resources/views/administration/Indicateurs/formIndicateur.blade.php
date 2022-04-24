@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Gestion des indicateurs",2) !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col col-md-6 form-group">
                    <label for="profil"> Profil</label>
                    <select class="form-control chosen-select" name="profil" id="profil" onchange="sendData('profil='+this.value,'{{route ('ajax.listeIndicateur')}}','indicateur')">
                        <option value="">[--Choisir un profil--]</option>
                        @foreach ($profils as $row)
                            <option value="{{$row['id']}}">{{$row['name']}}</option>';
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="indicateur"></div>
        </div>
    </div>
@endsection
