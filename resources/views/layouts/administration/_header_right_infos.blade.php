<ul class="nav navbar-nav mai-top-nav header-right-menu">
    <li class="nav-item dropdown">
        @include("layouts.administration._dropdown_messages")
    </li>
    
    <li class="nav-item">
        @include("layouts.administration._dropdown_notifications")
    </li>
    <li class="nav-item">
        @include("layouts.administration._dropdown_settings")
    </li>
    <li class="nav-item nav-setting-open"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa fa-tasks"></i></a>
         @include("layouts.administration._dropdown_settings_wrap")
    </li>
</ul>
