@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Gestion des tables pour exportation") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
            <div class="row">
                <div class="col-sm-12">
                    <div id="messageExport" class="alert "></div>
                </div>
                <form method="POST" >
                    @foreach($tables as $r)
                        @php($checked = in_array ($r,$tablesExports) ? "checked" : "")
                        <div class="col-sm-3 form-group">
                            <div class="form-group bt-df-checkbox pull-left" id="">
                                <label for="{{$r}}" class="">
                                    <input type="checkbox" class="pull-left radio-checked" value="{{$r}}" name="table" onclick="choisirTableExport(this.value,this.checked)" id="{{$r}}" {{$checked}}>
                                    {{$r}}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
    </div>

    <script>
        function choisirTableExport(table, etat) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            var url = "{{route ('ajax.ajouterTableExport')}}";
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
                    $('#messageExport').css('display','block').addClass(resultat.alerte).text(resultat.message);
                    setTimeout(function () {
                        $('#messageExport').css('display', 'none');
                    },4000);
                }
            })
        }
    </script>
@endsection
