
@foreach($tabMediasc['audio'] as $r)
    <div class="col-sm-4">
        @if(file_exists($cheminfichier.DIRECTORY_SEPARATOR.$r['fichier']))
            <figure>
                <figcaption>Listen to the T-Rex:</figcaption>
                <audio controls>
                    <source src="{{asset($cheminfichier.DIRECTORY_SEPARATOR.$r['fichier'])}}" type="audio/mpeg">

                    Your browser does not support the
                    <code>audio</code> element.
                </audio>
            </figure>
        @else
            <h3>Fichier inexistant!</h3>
        @endif
    </div>
@endforeach