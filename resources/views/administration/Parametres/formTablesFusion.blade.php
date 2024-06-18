@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Gestion des tables pour fusion") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="row">
            <div class="col-sm-12">
                <div id="messageExport" class="alert "></div>
            </div>
            <form method="POST" class="col-sm-12" style="max-height: 500px;overflow: auto;">
                @foreach($tables as $r)
                    @php($checked = in_array ($r,$tablesFusions) ? "checked" : "")
                    <div class="col-sm-3 form-group">
                        <div class="form-group bt-df-checkbox pull-left" id="">
                            <label for="table-{{$r}}" class="">
                                <input type="checkbox" class="pull-left radio-checked" value="{{$r}}" name="table" onclick="choisirTableFusion(this.value,this.checked)" id="table-{{$r}}" {{$checked}}>
                                {{$r}}
                            </label>
                        </div>
                    </div>
                @endforeach
            </form>
        </div>
    </div>
    
    <script>
        function choisirTableFusion(table, etat) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            var url = "{{route ('ajax.ajouterTableFusion')}}";
            var datas = {
                table : table,
                etat : etat
            };
            $.ajax({
                url: url,
                method: 'POST',
                data: datas,
                dataType: "JSON",
                success: function (resultat) {
                    ui.notify("Alerte Notification",resultat.message).closable().hide(8000).effect('slide');
                }
            })
        }
    </script>
@endsection
