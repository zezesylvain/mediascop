<div class="col-sm-3 form-group-inner chosen-select-single mg-b-20">
    <label for="localite">Localité <span>*</span></label>
    <select name="localite" class="chosen-select form-control" id="localite" onchange="chercherLieuLocalite(this.value)" required>
        <option value="">Choisir une localité</option>
        @foreach($localites as $r)
            <option value="{{$r['id']}}">{{$r['name']}}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-3 form-group-inner chosen-select-single mg-b-20" id="lieuItem">
    <label for="lieuLocalite">Lieu </label>
    <select name="lieuLocalite" class="form-control chosen-select" id="lieuLocalite">
        <option value="">Choisir un lieu</option>
    </select>
</div>
<div class="col-sm-3 form-group">
    <label for="investissement"> Investissement <span>*</span></label>
    <input  class="form-control" type="number" min="0" id="investissement" name="investissement" pattern="\d*" title="Mettez un nombre" required />
</div>

{!! $inputPubs !!}

<script>
    function chercherLieuLocalite(localite) {
        const url = "{{route ('ajax.getLieuLocalite')}}";
        $.ajax({
            type : "POST",
            url : url ,
            dataType: 'JSON',
            data : {
                localite : localite,
            },
            success : function(result){
                $('#lieuLocalite').empty().append('<option value="">Choisir une localité</option>');
                $.map( result, function( item ) {
                    $('#lieuLocalite').append('<option value="'+item.id+'">' + item.name + '</option>');
                });
                $('#lieuLocalite').trigger("chosen:updated");
            }
        });
    }
</script>

