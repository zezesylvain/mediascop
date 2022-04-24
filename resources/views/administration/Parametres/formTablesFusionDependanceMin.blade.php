<div class="col-sm-12">
    <hr class="trait-bleu">
</div>
<div class="col-sm-12">
    <div id="messageFusion" class="alert "></div>
</div>
<form method="POST" >
    @foreach($tables as $r)
        @if($r != $libelleTableFusion)
            @php($checked = in_array ($r,$listeTablesDependants) ? "checked" : "")
            <div class="col-sm-3 form-group">
                <div class="form-group bt-df-checkbox pull-left" id="">
                    <label for="table-{{$r}}" class="">
                        <input type="checkbox" class="pull-left radio-checked" value="{{$r}}" name="table" onclick="choisirTableFusionDependance('{{$tableFusionID}}',this.value,this.checked)" id="table-{{$r}}" {{$checked}}>
                        {{$r}}
                    </label>
                </div>
            </div>
        @endif
    @endforeach
</form>
<script>
    function choisirTableFusionDependance(table, tableDependant, etat) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var url = "{{route ('ajax.ajouterTableFusionDependant')}}";
        var datas = {
            table : table,
            tableDependant : tableDependant,
            etat : etat
        };
        $.ajax({
            url: url,
            method: 'POST',
            data: datas,
            dataType: "JSON",
            success: function (resultat) {
               
                //$('#messageFusion').css('display','block').addClass(resultat.alerte).text(resultat.message);
               // $('#notifications').attr('class','alert ').addClass(resultat.alerte);
                ui.notify("Alerte Notification",resultat.message).closable().hide(8000).effect('slide');
            }
        })
    }
</script>
