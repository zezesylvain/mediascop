<div style="overflow-x: auto;max-width: 326px;">
    @if($nbrDatas > 0)
        <label class="tcocher" style="">
            <input type="checkbox"> <strong>cocher tout</strong>
        </label>
        <?php $j = 0; ?>
        @foreach($datas as $r)
            <?php
            $j++;
            $checked = in_array ($r['id'],$listeSuivant) ? "checked" : "";
            ?>
            <label class="">
                <input name="annonceur[]" id="annonceur[{{$j}}]" value="{{$r['id']}}"
                       type="checkbox" {{$checked}}>
                {{$r['name']}}
            </label><br>
        @endforeach
    @endif
</div>

