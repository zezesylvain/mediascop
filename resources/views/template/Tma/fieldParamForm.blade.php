@extends("layouts.admin")
@section("container")
    <div class="about-sparkline">
        <div class="container-fluid">
            <h4 class="main-spark-hd">Paramétrage des champs</h4>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <?php
                            //dd($tables);
                            ?>
                            <label for="table">CHOISIR TABLES</label>
                            <input list="tables" class="form-control" type="text" id="choix_tables" onfocusout="sendData('table='+this.value,'{{route('ajax.getChampsTable')}}','tableItem')" onchange="sendData('table='+this.value,'{{route('ajax.getChampsTable')}}','tableItem')">
                            <datalist id="tables">
                                <option value="Toutes les tables">
                                @foreach($tables as $row)
                                    <option value="{{$row}}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-sm-8" id="tableItem">
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
