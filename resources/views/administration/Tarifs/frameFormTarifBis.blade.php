@extends("layouts.frame")
@section("frameContainer")
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form method="post" action="{{route('saisie.insertTarif')}}" autocomplete="on">
            <input type="hidden" name="media" value="{{$media}}">
            {!! csrf_field() !!}
            {!! $html !!}
        </form>
    </div>
@endsection
