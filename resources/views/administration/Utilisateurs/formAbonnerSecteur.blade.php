<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="all-form-element-inner">
            <form class="form-group-inner" role="form" method="POST" action=""
                  id="formAbonnerSecteur">
                {{ csrf_field() }}
                <input type="hidden" name="user" id="sectorUserID" value="">
               
                <input type="hidden" name="id" id="sectorPID" value="">
                <div class="col-sm-6 form-group">
                    <div class="chosen-select-single mg-b-20">
                        <label for="secteur">Secteurs</label>
                        <select id="secteur" class="chosen-select form-control" name="secteur" tabindex="-1">
                            <option value="">Choisir un secteur</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3" style="padding-top: 28px;">
                    <button type="submit" class="btn btn-primary btn-block">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#formAbonnerSecteur').on('submit',function (event) {
            event.preventDefault();
            var url = "{{route('ajax.abonnementSecteur')}}";
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
                    $('#userSecteur-'+data.userID+'').html(data.userSecteur) ;
                    $('#formAbonnerSecteur input').val();
                    setTimeout(function () {
                        $('#messageAbonnementItem').css('display', 'none').removeClass(data.alert);
                    },5000);
                }
            })
        })
    })
</script>
