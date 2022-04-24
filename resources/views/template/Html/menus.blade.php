<div class="col-sm-12 menu-horizontal">
    @foreach($lesSousMenus as $menu => $sousMenuRole)
       @php($active = $roleCourant == $sousMenuRole ? "danger" : "dark")
        <a  class="btn btn-{{$active}} btn-menus" href="{{url("/".$getChampTable($dbTable('DBTBL_ROLES'),$sousMenuRole,"uri"))}}">{{$getChampTable($dbTable('DBTBL_MENUS'),$menu)}}</a>
    @endforeach
    <hr>
</div>
