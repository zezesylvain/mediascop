<div class="col-sm-3 form-group-inner chosen-select-single mg-b-20">
    <label for="ville">Ville <span>*</span></label>
    <select name="ville" class="chosen-select form-control" id="ville" onchange="chercherCommune(this
        .value,'saisie')" required>
        <option value="">Choisir une ville</option>
        @foreach($villes as $r)
            <option value="{{$r['id']}}">{{$r['name']}}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-3 form-group-inner chosen-select-single mg-b-20" id="communeItem">
    <label for="commune">Commune <span>*</span></label>
    <select name="commune" class="form-control chosen-select" id="commune" required>
        <option value="">Choisir une commune</option>
    </select>
</div>
<div class="col-sm-2 form-group">
    <label for="nombre"> Nombre <span>*</span></label>
    <input  class="form-control" type="number" min="0" id="nombre" name="nombre" pattern="\d*" title="Mettez un nombre" required />
</div>
<div class="col-sm-3 form-group">
    <label for="investissement"> Investissement <span>*</span></label>
    <input  class="form-control" type="number" min="0" id="investissement" name="investissement" pattern="\d*" title="Mettez un nombre" required />
</div>

{!! $inputPubs !!}

<script>
    function chercherCommune(ville,k) {
        const url = "{{route ('ajax.getCommune')}}";
        $.ajax({
            type : "POST",
            url : url ,
            dataType: 'JSON',
            data : {
                localite : ville,
                k : k
            },
            success : function(result){
                $('#commune').empty().append('<option value="">Choisir une commune</option>');
                $.map( result, function( item ) {
                    $('#commune').append('<option value="'+item.id+'">' + item.name + '</option>');
                });
                $('#commune').trigger("chosen:updated");
            }
        });
    }
    function getListeDesPanneaux(commune) {
        var url = "{{route ('ajax.getListeDesPanneaux')}}";
        $.ajax({
            type : "POST",
            url : url ,
            dataType: 'JSON',
            data : {
                commune : commune
            },
            success : function(result){
                $('#listeDesPanneauxItem').html(result.panneaux);
                if (!result.k){
                    $('#format').empty().append('<option value="">Choisir un format</option>');
                    $.map( result.formats, function( item ) {
                        $('#format').append('<option value="'+item.id+'">' + item.name + '</option>');
                    });
                    $('#format').trigger("chosen:updated");
                }
                $('#communePanneau').val($('#commune').val());
            }
        });
    }

    function searchListeDesPanneaux(commune,format,search) {
        var url = "{{route ('ajax.getListeDesPanneaux')}}";
        $.ajax({
            type : "POST",
            url : url ,
            dataType: 'JSON',
            data : {
                commune : commune,
                format : format,
                search : search
            },

            success : function(result){
                $('#listeDesPanneauxItem').html(result.panneaux);
            }

        });
    }
</script>

