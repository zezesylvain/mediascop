@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Gestion des Tarifs") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="media">Medias</label>
                    <select name="media" id="media" data-placeholder="Choisissez un m&eacute;dia" class="chosen-select form-control" tabindex="-1" onchange="sendData('media='+this.value,'{{route ('ajax.getFormTarif')}}','formTarifItem')">
                        <option value="">Choisir un média</option>
                        @foreach($medias as $row)
                            <option value="{{$row['id']}}">{{$row['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr>
                    <div id="formTarifItem"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
