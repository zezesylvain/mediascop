@if(!isset($iii))
<span id="media-{{$itemid}}">
    <a href="#media-{{$itemid}}" ondblclick="sendData('id={{$itemid}}&value={{$itemmedia}}&col=media&table={{$tableDoCampagne}}&colname=name&tableMedia={{$tableMedia}}&pid=media-{{$itemid}}','{{$route}}','media-{{$itemid}}')">
        {!! $tableauDesMedias[$itemmedia]['name'] !!}
    </a>
</span>
@else
    <select class="form-control" onchange="sendData('fichier={{$basename}}&type={{$extension}}&name={{$filename}}&campagnetitle={{$campagnetitleID}}&media=' + this.value, '{{$route1}}', 'mediainSevernotinbd')">
    <option>S&eacute;lectionner un m&eacute;dia</option>
    {!! $medopt !!}
    </select>
@endif
