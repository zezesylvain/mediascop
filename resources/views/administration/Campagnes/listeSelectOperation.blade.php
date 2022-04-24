<div class="chosen-select-single mg-b-20 form-group">
    <label  for="opfilteroperation">Operations</label>
    <select name="opfilteroperation" id="opfilteroperation" data-placeholder="" class="chosen-select form-control" tabindex="-1">
        <option value="0">Choisir une opération</option>
        @foreach($listeDesOperations as $r)
            <option value="{{$r['id']}}" >{{$r['name']}}</option>
        @endforeach
    </select>
</div>
