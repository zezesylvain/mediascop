<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="chosen-select-single mg-b-20">
        <label for="{{$champ}}"> {{$libelle}}</label>
        <select name="{{$champ}}" class="select2_demo_3 form-control" id="{{$champ}}">
            <option>--------</option>
            @foreach($list as $r)
                {{$elem = substr($r, 1, -1)}}
                <?php $selected = $r == $valeur ? "selected=selected" : ""; ?>
                <option value="{{$r}}" {{$selected}}>{{$elem}}</option>";
            @endforeach;
        </select>
    </div>
</div>
