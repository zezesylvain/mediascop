<tr>
    <td>{{$i}}</td>
        <?php
        $itemId = $rg["id"];
        $position = "test-$i-$itemId";
        $tableGp =  $getTableName($dbTable('DBTBL_GROUPEMENUS'));

        $itemId2 = $rg["id"];
        $position2 = "icone-$i-$itemId2";
        $tableIcone =  $getTableName($dbTable('DBTBL_ICONES'));
        ?>
        <td class="">
            <span id="test-{{ $i }}-{{ $itemId }}">
                {!! $inlineTexte($itemId,$rg['name'],'name',$tableGp,"texte",$position) !!}
            </span>
        </td>
     <td>
         {!! $ec($rg['icone'],'icone') !!}
     </td>
    <td>
        {!! $chevronUp  !!}
        {!! $chevronDown  !!} <span style="color: #FF0000;font-weight: 800;">({{$rg['rang']}})</span>
    </td>
    <td>
        <a href="{{route('modifierGrpMenu',[$rg['id']])}}" title="modifier le groupe de menu"> <i class="fa fa-pencil"></i></a>
        <a href="#" title="Supprimer le menu" onclick="sendData('grpmenu={{$rg['id']}}','{{route('ajax.delGrpMenus')}}','ordonnerItem')" > <i class="fa fa-trash rouge"></i> </a>
    </td>
</tr>