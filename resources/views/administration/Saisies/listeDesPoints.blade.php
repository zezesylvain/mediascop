<div class="row" style="margin-top: 5px;border: 0 solid #000;border-radius: 6px;padding: 3px;">
    <div class="col-md-12">
        <div class="static-table-list">
            <table class="table">
                <thead>
                <tr>
                    <th style="width: 50%;">Latitude</th>
                    <th style="width: 50%;">Longitude</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$data['latitude']}}</td>
                    <td>{{$data['longitude']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div id="messageHM" class="alert"></div>
    </div>
    <div class="col-md-12">
        <form action="" method="post" id="formAddLngLatItem">
            @csrf
            <input type="hidden" name="latitude" value="{{$data['latitude']}}">
            <input type="hidden" name="longitude" value="{{$data['longitude']}}">
            <div class="row">
                <div class="col-md-5 form-group">
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" id="ville" class="form-control" required>
                </div>
                <div class="col-md-5 form-group">
                    <label for="commune">Commune</label>
                    <input type="text" name="commune" id="commune" class="form-control">
                </div>
                <div class="col-md-12 form-group">
                    <label for="detail_emplacement">Detail Emplacement</label>
                    <textarea name="detail_emplacement" id="detail_emplacement" class="form-control" rows="4"></textarea>
                </div>
                <div class="col-md-2 form-group">
                    <button type="submit" class="btn btn-primary" style="margin-top: 23px;" >Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#formAddLngLatItem').submit(function (event) {
            event.preventDefault();
            var url = "{{route('ajax.ajouterPointHorsMedia')}}";
            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#messageHM').css('display', 'block').html(data.message).addClass(data.alert);
                    setTimeout(function () {
                        $('#messageHM').css('display', 'none');
                    },4000);
                    $('#listeDesPointsHM').html(data.listeDesPoints);
                    $('#formAddLngLatItem input').val("");
                    $('#InformationproModalalert').modal('toggle');
                }
            });
        })
    })
</script>
