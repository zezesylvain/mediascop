<?php
$latDefault = $data->latitude ?? 5.356149786699246;
$lngDefault = $data->longitude ?? -4.007166835937483;
?>
<div class="col-md-6" xmlns="http://www.w3.org/1999/html">
    <div id="mapCanvas" style="width:100%; height:400px"></div>
</div>
<div class="col-md-6">
    <input type="hidden" name="support" value="{{$supports[0]['id']}}">
    <div class="row">
<!--            <div class="col-sm-6 form-group-inner">
                <label for="type_promo">Type de promos</label>
                <select name="type_promo" id="type_promo" class="form-control chosen-select" required>
                    <option value="">Choisir une promo</option>
                    @foreach($typeDePromos as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>-->
<!--            <div class="col-sm-6 form-group-inner">
                <label for="type_service">Type de services</label>
                <select name="type_service" id="type_service" class="form-control chosen-select" required>
                    <option value="">Choisir un service</option>
                    @foreach($typeDeService as $r)
                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>-->
<!--            <div class="col-sm-6 form-group-inner">
                <label for="format">Format</label>
                <select name="format" id="format" class="form-control chosen-select" required>
                    <option value="">Choisir un format</option>
                    @foreach($formats as $r)
                        @php($sm = $r['id'] === $formatID ? "selected" : "")
                        <option value="{{$r['id']}}" {{$sm}}>{{$r['name']}}</option>
                    @endforeach
                </select>
            </div>
    </div>-->
<!--    <div class="row">
        <div class="col-sm-12">
            <hr class="trait-bleu">
        </div>
    </div>-->
    <div class="row">
        <div class="col-sm-12" id="latlngitem">
            <div class="col-xs-12">
                    <h4 style="color: #ff0000;text-decoration: underline;">Choisir la/les position(s) de la campagne sur la carte et valider !</h4>
            </div>
        </div>
        <div class="form-group-inner col-sm-4">
            <label for="latItem">Latitude:</label>
            <input value="{{$latDefault}}" type="text" class="form-control"  id="latItem" onchange="sendData('lat='+this.value,'{{route('ajax.changerCoord','lat')}}', 'latDivItem')" >
        </div>
        <div class="form-group-inner col-sm-4">
            <label for="lngItem">Longitude:</label>
            <input value="{{$lngDefault}}" type="text" class="form-control"  id="lngItem" onchange="sendData('lng='+this.value,'{{route('ajax.changerCoord','lng')}}', 'lngDivItem')" >
        </div>
        <div class="col-sm-3 btn-group pt-4" style="padding-top: 22px;">
            <a href="#" class="btn btn-custon-four btn-danger" data-toggle="modal" data-target="#InformationproModalalert"  onclick="ajouterPointHorsMedia($('#lngItem').val(),$('#latItem').val())"> <i class="fa fa-map"></i> Ajouter un lieu</a>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
           <div id="listeDesPointsHM"></div>
        </div>
    </div>
</div>

<script>
    function ajouterPointHorsMedia(lng,lat) {
        var url = "{{route ('ajax.choisirCoordHorsMedia')}}";
        $.ajax({
            url: url,
            method: 'POST',
            data: {lng: lng,lat:lat},
            dataType: "JSON",
            success: function (data) {
                $('#choixCoordMediaItem').html(data.formLngLat);
            }
        });
    }

</script>

    <div id="InformationproModalalert" class="modal modal-adminpro-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-body">
                    <span class="adminpro-icon adminpro-informatio modal-check-pro information-icon-pro"> </span>
                    <h2>Ajouter un point et valider!</h2>
                    <div id="choixCoordMediaItem"></div>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" href="#">Fermer</a>
                </div>
            </div>
        </div>
    </div>
</div>
