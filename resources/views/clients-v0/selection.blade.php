
@foreach ($selection as $key => $valueselection)
    <div class="row">
        <div class="col-xs-4 col-sm-3 col-md-2">
            {{ucfirst($key)}} :
        </div>
        <div class="col-xs-8 col-sm-9 col-md-10">
            {!! $valueselection  !!}
        </div>
    </div>
@endforeach
@include("woody.Html.afficherMessage")
