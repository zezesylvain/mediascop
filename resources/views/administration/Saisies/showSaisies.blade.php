@foreach($medias as $r)
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        {!! \App\Http\Controllers\Administration\SaisieController::saisiesDuJour ($r['id'],$date,$user,$valide) !!}
    </div>
@endforeach
