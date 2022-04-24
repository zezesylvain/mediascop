@if(\Illuminate\Support\Facades\Auth::check())
<div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a class="smalllinkheader" href="{{url('/home')}}">
                <i class="fa fa-fw fa-home"></i> ACCUEIL
            </a>
        </div>
    </div>
    @if(\Illuminate\Support\Facades\Auth::user()->profil == 5)
        <div class="panel panel-default">
            <div class="panel-heading">
                <a class="smalllinkheader" href="{{route('user.monCompte')}}">
                    <i class="fa fa-fw fa-user"></i> MON COMPTE
                </a>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <a class="smalllinkheader" href="{{route('ftp')}}">
                    <i class="fa fa-fw fa-file-movie-o"></i> FTP
                </a>
            </div>
        </div>
    @endif
</div>
@endif

