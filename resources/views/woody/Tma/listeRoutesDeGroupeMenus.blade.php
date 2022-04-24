<div class="col-sm-12">
    <h4 style="text-decoration: underline">Liste des routes du groupe de menu <b>{{$grpMenuName}}</b></h4>
    <ul class="list-group">
        @foreach($routes as $route)
            @if($route['est_menu'] == 1)
            <li style="background-color: rgba(0,0,0,0.1);"><b>{{$route['name']}}</b> | {{$route['uri']}}</li>
            @endif
            @if($route['est_menu'] == 0)
            <li style="background-color: rgba(255,255,255,0);"><b>{{$route['name']}}</b> | {{$route['uri']}} | <a href="#" onclick="sendData('grpRouteID={{$route['groupeRouteID']}}&grpMenuID={{$route['groupemenu']}}','{{route('ajax.deleteGrpMenuRoute')}}','routeGroupeItem')" style="color:red;" title="Supprimer"><i class="fa fa-trash"></i></a>
            </li>
            @endif
        @endforeach
    </ul>
</div>