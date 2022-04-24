@if($photoProfil == "")
    <a href="#"><i class="fa fa-user fa-5x"></i> </a>
@else
    <a href="#"><img src="{{$photoProfil}}" width="150px" height="150px" alt="photo de profil"></a>
@endif
