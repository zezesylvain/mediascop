<div class="chosen-select-single mg-b-20">
    <label for="{{ $champ }}"> {{ $libelle ?? ucfirst($champ) }}</label>
    <select id="{{ $champ }}" class="chosen-select form-control" name="{{ $champ }}" tabindex="-1">
        <option value="">--Choisir--</option>
        @foreach($data as $r)
            @php($selected = $selectedValue == $r['id'] ? "selected='selected'" : "")
            <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
        @endforeach
    </select>
    @if ($errors->has($champ))
        <span class="help-block">
        <strong>{{ $errors->first($champ) }}</strong>
    </span>
    @endif
</div>