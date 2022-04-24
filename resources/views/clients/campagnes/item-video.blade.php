
@foreach($tabMediasc['video'] as $r)
    <div class="col-sm-4">
        <video controls src="{{asset($cheminfichier.DIRECTORY_SEPARATOR.$r['fichier'])}}">Ici la description alternative</video>
    </div>
@endforeach