<div class="chosen-select-single mg-b-20">
    <label for="commune">Commune</label>
    <select class="form-control chosen-select" name="commune" id="commune" onchange="{{$send}}" required>
        <option value="">Choisir une commune</option>
        @foreach($communes as $r)
            <option value="{{$r['id']}}">{{$r['name']}}</option>
        @endforeach
    </select>
</div>
