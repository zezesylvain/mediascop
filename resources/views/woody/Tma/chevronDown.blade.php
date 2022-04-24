
@if(isset($mod))
    <a href="#"  onclick="sendData('direction=ASC&groupemenu={{$grpMenuId}}&rang={{$rangGrpMenu}}', '{{route('ajax.ordonnergrpmenu')}}',  'ordonnerItem')">
        <span class="fa fa-chevron-down"></span>
    </a>
@else
    <a href="#"  onclick="sendData('direction=ASC&groupemenu={{$grpMenuId}}&menuid={{$menuId}}&rang={{$rangMenu}}', '{{route('ajax.ordonnermenu')}}',  'ordonnerItem')">
        <span class="fa fa-chevron-down"></span>
    </a>
@endif