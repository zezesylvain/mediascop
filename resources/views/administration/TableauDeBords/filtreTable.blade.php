@if($html != "")
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <hr class="trait-bleu">
        <h4>Filtre sur la table</h4>
        <div class="row">
            <form action="{{route ('dashbord.formExportQuery')}}" method="post" id="filtreTableItem">
                {!! csrf_field () !!}
                <input type="hidden" name="table" value="{{$table}}">
                {!! $html !!}
                <div class="col-sm-12"></div>
                <div class="col-sm-3" style="padding-top: 25px;">
                    <button class="btn btn-primary btn-block" type="submit">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function operateurChamps(val,table,item) {
            var url = "{{route ('ajax.getOperateurChamps')}}";
            var select_elem = $('#'+item+'');
            var attrName = select_elem.attr('name');
            $.ajax({
                type : "POST",
                url : url ,
                data : {
                    param : val,
                    table : table
                },
                dataType: "JSON",
                success : function(result){
                   if (result === true){
                       select_elem.chosen('destroy');
                       select_elem.removeClass(' chosen-select');
                       select_elem.attr('multiple','multiple');
                       select_elem.attr('name',attrName+'[]');
                       select_elem.chosen();
                   }else {
                       select_elem.chosen('destroy');
                       select_elem.removeAttr('multiple');
                       select_elem.addClass(' chosen-select');
                       attrName = attrName.replace('[]',"");
                       select_elem.attr('name',attrName);
                       select_elem.chosen();
                   }
                    select_elem.trigger("chosen:updated");
                }
        
            });
        }
    </script>
@endif
