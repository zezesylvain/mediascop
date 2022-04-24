<li class="{{$active}}">
    <a class="has-arrow" href="#" {{$expansion}}>
        <i class="fa big-icon fa-{{$licone}} icon-wrap"></i>
        <span class="mini-click-non">{{$label}}</span>
    </a>
    <ul class="submenu-angle {{$show}}" {{$expansion}}>
        {!! $li !!}
    </ul>
</li>

{{--
<li class="nav-item {$active}" data-toggle="tooltip" data-placement="right" title="{$label}">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#{$id}" data-parent="#{$idMenu}"{$expansion}>
        <i class="fa fa-fw fa-{$licone}"></i>
        <span class="nav-link-text">{$label}</span>
    </a>
    <ul class="sidenav-second-level collapse{$show}" id="{$id}">
        {$li}
    </ul>
</li>
--}}
