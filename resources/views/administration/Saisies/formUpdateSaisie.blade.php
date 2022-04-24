<form method="post" action="{{route('saisie.updateSaisie')}}" autocomplete="on">
    {!! csrf_field() !!}
    <input type="hidden" name="pk" value="{{$pubID}}">
    <input type="hidden" name="media" value="{{$mediaID}}">
    <input type="hidden" name="table" value="{{$table}}">
    <h4>Modifier Saisie {{$lemedia}}</h4>
    <hr class="trait-bleu">
    <div class="row">
        {!! $html !!}
    </div>
    <hr class="trait-bleu">
    <div class="row">
        <div class="col-xs-12">
            {!! \App\Http\Controllers\core\FormController::champSubmit("Valider") !!}
        </div>
    </div>
</form>
