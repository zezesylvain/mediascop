@if(isset($iii))
    <span id="visueldelete-{{$iii}}"><a href="#" onclick="sendData('fichier={{$basename}}&campagnetitle={{$campagnetitleID}}', '{{$rdel}}', 'mediainSevernotinbd')"><i class="fa fa-trash"></i></a></span>
@else
    <span id="visueldelete-{{$itemid}}">
        <a href="#visueldelete-{{$itemid}}" onclick="sendData('fichier={{$basename}}&bdid={{$itemid}}&campagnetitle={{$campagnetitleID}}', '{{$rdel}}', 'mediainSevernotinbd')"><i class="fa fa-trash"></i>
        </a>
    </span>
@endif
