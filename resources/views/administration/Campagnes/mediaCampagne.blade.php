<div>
    @php($src = asset ("upload".DIRECTORY_SEPARATOR."campagnes".DIRECTORY_SEPARATOR.$campagnetitleID.DIRECTORY_SEPARATOR.$basename))
    @if(in_array (strtolower ($extension),$imageext))
        <img src="{{$src}}" alt="{{$filename}}" width="100px" height="80px">
    @elseif(array_key_exists (strtolower ($extension),$audioext))
        <audio controls="controls">
            <source src="{{$src}}" type="audio/ogg" />
            <source src="{{$src}}" type="audio/mp3" />
            Votre navigateur ne supporte pas la balise AUDIO.
        </audio>
    @elseif(array_key_exists (strtolower ($extension),$videoext))
        <video width="200" height="100" controls="controls">
            <source src="{{$src}}" type="video/mp4" />
            <source src="{{$src}}" type="video/webm" />
            <source src="{{$src}}" type="video/ogg" />
            Ici l'alternative à la vidéo : un lien de téléchargement, un message, etc.
        </video>
    @else
        <i class="fa fa-file-o fa-3x"></i>
    @endif
</div>
