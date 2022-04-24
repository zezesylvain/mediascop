@extends("layouts.admin")
@section("container")
    <div class="about-sparkline">
        <div class="container-fluid">
            <div class="row">
                {!! $titreHtml ('Tables et champs par defaut',2) !!}
                <div class="col-sm-12">
                    <div id="messageItem" class="alert" style="display: none;"></div>
                </div>
                <div class="col-sm-4">
                    <label for="tables">CHOISIR TABLES</label>
                    <select name="" id="tables" class="form-control chosen-select" onchange="getChampsTable(this.value)">
                        <option value="">Choisir une table</option>
                        @foreach($tables as $row)
                            <option value="{{$row}}">{{$row}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for=""></label>
                    <table class="table table-responsive table-bordered table-striped" id="tableDefaultFieldsItem">
                         <thead>
                         <tr>
                             <th style="width: 80%;">CHAMPS</th>
                             <th style="width: 20%;">ACTION</th>
                         </tr>
                         </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getChampsTable(donnee) {
            var url = "{{route ('ajax.getListTableField')}}";
            $.ajax({
                type : "GET",
                url : url ,
                data : {
                    table : donnee,
                },
                dataType: "JSON",
                success : function(result){
                    $('#tableDefaultFieldsItem tbody').empty().append(result.listeChamps);
                }
            });
        }
        
        function addTableDefaultField(table,donnee) {
            var url = "{{route ('ajax.addTableField')}}";
            $.ajax({
                type : "POST",
                url : url ,
                data : {
                    latable : table,
                    field : donnee.value,
                    etat : donnee.checked
                },
                dataType: "JSON",
                success : function(data){
                    $('#messageItem').css('display', 'block').html(data.message).addClass(data.alerte);
                    ;
                }
            });
        }
    </script>
@endsection
