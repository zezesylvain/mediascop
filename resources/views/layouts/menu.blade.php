<ul class="metismenu" id="menu1">
        @foreach($lesMenus AS $r)
            <li @if($r['expanded']) class="active"@endif>
                <a class="has-arrow" href="#">
                    <i class="fa big-icon fa-{{ $r['icone'] }} icon-wrap"></i>
                    <span class="mini-click-non">{{ $r['groupemenu'] }}</span>
                </a>
                <ul class="submenu-angle  collapse" 
                aria-expanded="@if($r['expanded']){{'true'}} @else {{'false'}} @endif">
                    @foreach($r['sous-menus'] AS $it)
                        <li @if($it['active']) class="active" @endif>
                            <a title="{{ $it['active'] }}" href="{{ env('APP_URL').'/'.$it['url'] }}"
                            @if($it['menu_target'] != '') target="{{ $it['menu_target'] }}"@endif><i
                                    class="fa fa-{{ $it['icone'] }} sub-icon-mg" aria-hidden="true"></i>
                                    <span class="mini-sub-pro">{{ $it['title'] }}</span></a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>