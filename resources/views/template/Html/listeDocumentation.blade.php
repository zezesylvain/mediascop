@extends("layouts.admin")
@section("container")
    <div class="row">
        <div class="col-sm-12">
            <h2>DOCUMENTATION</h2><hr>
        </div>
        <div class="col-sm-4">
            <div class="list-group">
                @foreach($grpMenus as $r)
                    <a href="#" class="list-group-item list-group-item-action" onclick="sendData('grp={{$r['id']}}','{{route('ajax.bdcForm')}}','documentItem')">{{$r['name']}}</a>
                @endforeach
            </div>
        </div>
        <div class="col-sm-8">
            <div id="documentItem"></div>
        </div>
    </div>
@endsection