<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="chosen-select-single mg-b-20">
        <label for="{{$champ}}"> {{$libel}}</label>
        <select name="{{$champ}}" class="form-control" required id="{{$champ}}">
            <option value="">------</option>
            @foreach($requete as $r)
                <?php $selected = $r->id == $value ? "selected=selected" : ""; ?>
                <option value="{{$r->id}}" {{$selected}}>{{$r->name}}</option>";
            @endforeach;
        </select>
    </div>
</div>
