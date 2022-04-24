@extends("layouts.admin")
@section("content")

    <div class="row">
        <div class="col-sm-12">
            <h4>EMAILLING</h4>
            <div class="row">
                <div class="col-sm-6">
                    <label for="recherchez"><i class="fa fa-search"></i> Rechercher</label>
                    <input type="text" onkeyup="sendData('v='+this.value,'{{route('ajax.rechercher')}}','tableEmail')" class="form-control" id="recherchez">
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <hr>
            {!! $table !!}
        </div>
    </div>
@endsection