<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="all-form-element-inner">
            <form class="form-group-inner" role="form" method="POST" action="" id="formAbonnerSociete">
                {{ csrf_field() }}
                <input type="hidden" name="user" id="societeUserID" value="">
                <input type="hidden" name="id" id="societePID" value="">
                <div class="col-sm-6 form-group">
                    <div class="chosen-select-single mg-b-20">
                        <label for="annonceur">Annonceur</label>
                        <select id="annonceur" class="chosen-select form-control" name="annonceur" tabindex="-1">
                            <option value="">Choisir un annonceur</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3" style="padding-top: 28px;">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#formAbonnerSociete').on('submit',function (event) {
            event.preventDefault();
            var url = "{{route('ajax.abonnementSociete')}}";
            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#messageAbonnementItem').css('display', 'block').html(data.message).addClass(data.alert);
                    $('#userSociete-'+data.userID+'').html(data.userSociete)
                    setTimeout(function () {
                        $('#messageAbonnementItem').css('display', 'none').removeClass(data.alert);
                    },8000);
                }
            })
        })
    })
</script>
