
<fieldset style="min-height: 250px;" class="box-date">
    <legend><h4>Période</h4></legend>
    <div class="row">
        <div class="col-sm-12">
            <label for="startPicker" class="section-name">Début</label>
            <input type="text" id="startPicker" name="date_debut" class="form-control" value="{{ $date_debut }}">
        </div>
        <div class="col-sm-12" style="margin-top: 20px;">
            <label class="section-name" for="endPicker">Fin</label>
            <input type="text" id="endPicker" name="date_fin" class="form-control" value="{{ $date_fin }}">
        </div>
    </div> <!-- END row -->
</fieldset>

<script>
        function changeDate(donnee,key) {
            var url = "{{route ('ajax.getReportFormDatas')}}";
            $.ajax({
                type : "POST",
                url : url ,
                data : {
                    donnee : donnee,
                    key : key
                },
                dataType: "JSON",
                success : function(result){
                
                }
                
            });
        }
</script>
