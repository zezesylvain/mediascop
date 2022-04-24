@php($dates = $makeListeDate())
<option value="">Choisir une date</option>
@foreach($dates as $key => $date)
    @php($selected = $key == $today ? "selected='selected'" : "")
    <option value="{{$key}}" {{$selected}}>{{$date}}</option>
@endforeach
