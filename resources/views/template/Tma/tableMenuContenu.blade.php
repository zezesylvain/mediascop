<?php
   $itemIdM = $r['id'];
   $positionM = "test-$i-$itemIdM";
   $tableMenu =  $getTableName($dbTable('DBTBL_MENUS'));
?>
<tr>
    <td>{{$i}}</td>
    @if($y == 1)
        <?php
        $itemId = $rg["id"];
        $position = "test-$y-$itemId";
        $tableGp =  $getTableName($dbTable('DBTBL_GROUPEMENUS'));
        $tableMenu =  $getTableName($dbTable('DBTBL_ROLES'));
        ?>
        <td rowspan="{{$j}}" class="centerHorizontal">
            <span id="test-{{ $y }}-{{ $itemId }}">
                {!! $inlineTexte($itemId,$rg['name'],'name',$tableGp,"texte",$position) !!}
            </span>
        </td>
    @endif
    <td>
        <span id="test-{{ $i }}-{{ $itemIdM }}">
            {!! $r['nom'] !!}
            {{--{!! $inlineTexte($itemIdM,$r['nom'],'name',$tableMenu,"texte",$positionM) !!}--}}
        </span>
    </td>
     <td>
         {{$r['role']}}
     </td>
    <td>
        {{$r['level']}}
    </td>
    <td>
       {!! $chevronUp  !!}
        {!! $chevronDown  !!} <span style="color: #FF0000;font-weight: 800;">({{$r['rgmenu']}})</span>
    </td>
    <td>
        <a href="{{route('modifierMenu',[$r['id']])}}" title="modifier le menu"  data-toggle="" data-target=""> <i class="fa fa-pencil"></i></a>
        <a href="#" title="Supprimer le menu" onclick="sendData('menu={{$r['id']}}','{{route('ajax.delMenus')}}','ordonnerItem')" > <i class="fa fa-trash rouge"></i> </a>
    </td>
</tr>