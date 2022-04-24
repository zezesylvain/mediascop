<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
    <i class="fa fa-user adminpro-user-rounded header-riht-inf" aria-hidden="true"></i>
    <span class="admin-name">{!! $userName () !!}</span>
    <i class="fa fa-angle-down adminpro-icon adminpro-down-arrow"></i>
</a>
<ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
    @if(in_array (\Illuminate\Support\Facades\Auth::user ()->profil,[1]))
        <li><a href="{{route ('CreerUtilisateur')}}"><span class="fa fa-home author-log-ic"></span>Créer un utilisateur</a>
        </li>
    @endif
    <li>
        <a href="{{route ('user.monProfil')}}"><span class="fa fa-user author-log-ic"></span>Mon Profil</a>
    </li>

    <li>
        <a href="#"><span class="fa fa-cog author-log-ic"></span>Paramètres</a>
    </li>
    <li><a href="#"
           onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"><span class="fa fa-lock author-log-ic"></span>Déconnexion</a>
    </li>
</ul>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

