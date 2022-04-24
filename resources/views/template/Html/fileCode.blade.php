@if (in_array(strtolower($ext), $imageext))
    <li>
        <div class="slide-body" data-group="slide">
            <div class='img img-responsive'>
                <img class="img-responsive" style="width: 70%;" src="{{$file}}" alt="">
            </div>
        </div>
    </li>
@endif
@if (array_key_exists(strtolower($ext), $audioext))
    <audio controls>
        <source src="{{$file}}" type="{{$audioext[$ext]}}">
        Votre navigateur ne peut pas lire ce fichier audio.
    </audio>
@endif
@if (array_key_exists(strtolower($ext), $videoext))
    <video width="100%" controls>
        <source src="{{$file}}" type="{{$videoext[$ext]}}">
        Votre navigateur ne peut pas lire ce fichier vidéo.
    </video>
@endif
