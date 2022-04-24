@if($vue == "form")
    <div class="col-sm-3" style="padding: 10px;text-align: center;">
        <h4>Logo:</h4>
    </div>
    <div class="col-sm-6" style="padding: 10px;text-align: center;border: 1px solid #0A0A0A;">
        <img src="{{asset ($src)}}" alt="" width="100px" height="100px">
    </div>
    <div class="col-sm-3" style="padding: 10px;border: 0px solid #0A0A0A;">
        <a data-toggle="tooltip" title="Modifier le logo" class="pd-setting-ed" onclick="sendData('var=1','{{route ("ajax.changerLogo")}}','logoInputItem')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
    </div>
@endif

@if($vue == "table")
    <img src="{{asset ($src)}}" alt="" width="50px" height="50px">
@endif

