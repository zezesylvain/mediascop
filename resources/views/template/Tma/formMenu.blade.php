@extends("layouts.admin")
@section("container")
    <div class="about-sparkline mg-tb-10">
        <div class="container-fluid">
            <h3 class="main-spark-hd">GESTION DU MENU</h3>
            <div class="row">
                @if(!isset($lemenu))
                    <div class="col-lg-12 col-md-10 col-sm-12">
                        <h4><i class="fa fa-paragraph"></i> Nouveau menu</h4><hr>
                        <form class="row" method="post" action="{{route("storeMenu")}}">
                            {{csrf_field()}}
                            <div class="form-group col-sm-4">
                                <label for="groupemenu">Groupe de menu</label>
                                <select  name="groupemenu"  class="form-control"  required id="groupemenu" onchange="sendData('grpmenu='+this.value,'{{route('ajax.getFormMenu')}}','formMenuItem')">
                                    <option value="">-----------</option>
                                    @foreach($grpMenus as $r)
                                        <option value="{{$r['id']}}">{{$r['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12"><div id="formMenuItem"></div></div>
                        </form>
                        <hr>
                    </div>
                @else
                    <div class="col-sm-12">
                        <h4><i class="fa fa-paragraph"></i> Modifier menu</h4><hr>
                        <form class="row" method="post" action="{{route("storeMenu")}}">
                            {{csrf_field()}}
                            <input type="hidden" name="pid" value="{{$lemenu['id']}}">
                            <div class="form-group col-sm-4">
                                <label for="groupemenu">Groupe de menu</label>
                                <select  name="groupemenu"  class="form-control"  required id="groupemenu" onchange="sendData('grpmenu='+this.value+'&grpmenuOld={{$lemenu['groupemenu']}}&v=modif','{{route('ajax.getFormMenu')}}','rangMenuItem')">
                                    <option value="">-----------</option>
                                    @foreach($grpMenus as $r)
                                        @php($selected = $r['id'] == $lemenu['groupemenu'] ? 'selected="selected"' : '')
                                        <option {{$selected}} value="{{$r['id']}}">{{$r['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <input type="hidden" value="{{$lemenu['rang']}}" name="rang">
                                    <input type="hidden" value="9" name="icone">
                                    <div class="form-group col-sm-3">
                                        <label for="role">Roles</label>
                                        <select  name="role"  class="form-control"  required id="role">
                                            <option>-------</option>
                                            @foreach($roles as $v)
                                                @php($selected = $v['id'] == $lemenu['role'] ? 'selected="selected"' : '')
                                                <option {{$selected}} value="{{$v['id']}}">{{$v['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="name">Libellé</label>
                                        <input type="text" name="name"  class="form-control" value="{{$lemenu['name']}}"  required id="name">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="level">LEVEL</label>
                                        <input type="number" name="level_menu" min="0" max="999999" step="10"  class="form-control"  required id="level" value="{{$lemenu['level_menu']}}">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="target">Target</label>
                                        <select name="menu_target" id="target" class="form-control">
                                            @foreach($targetMenu as $r)
                                                @php($selected = $r['id'] == $lemenu['menu_target'] ? : '')
                                                <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="rangMenuItem"></div>
                                </div>
                            </div>
                            {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
                        </form>
                        <hr>
                    </div>
        
                @endif
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-th-list"></i> Liste des menus et groupe de menus</h4><hr>
                    {!! $tableauMenu !!}
                </div>
            </div>
        </div>
    </div>
    
    <div id="myMenuModal" class="modal modal-form fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <div id="modifierMenuItem"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection
