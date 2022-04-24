<div id="dateItem{{$numero}}">
    <input type="text" name="date[]" placeholder="jj/mm/aaaa" class="form-control" id="date{{$numero}}" onchange="sendData('date='+this.value,'{{route("ajax.validerDate")}}','dateItem{{$numero}}')" required>
</div>
