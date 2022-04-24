<div style="overflow-x: auto;max-width: 326px;">
    @if(count($datas) > 0)
        <label class="tcocher" style="">
            <input type="checkbox"  onclick="checkboxChecker('annonceur',this.checked,'{{ count($datas) }}');">
            <strong>cocher tout</strong>
        </label>
        <?php $j = 0; ?>
        @foreach($datas as $r)
            <?php
            $j++;
            $checked = in_array ($r['id'],$listeAnnonceurs) ? "checked" : "";
            ?>
            <label class="">
                <input name="annonceur[]" id="annonceur[{{$j}}]" value="{{$r['id']}}"
                       type="checkbox" {{ $checked }}>
                {{$r['name']}}
            </label><br>
        @endforeach
    @endif
</div>

