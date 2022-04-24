<div class="col-sm-4">
    @if(count($tabMediasc['img']) > 1)
        <div class="text-center slideshow img img-responsive">
            <ul>
                @foreach($tabMediasc['img'] as $doc)
                    @php($fichier = $cheminfichier.DIRECTORY_SEPARATOR.$doc['fichier'])
                    @if(file_exists($fichier))
                        <li>
                            <img src="{{asset($fichier)}}" alt="" width="350" height="200" />
                        </li>
                    @else
                        <h4>Fichier inexistant!</h4>
                    @endif
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center img img-responsive">
            @php($fichier = $tabMediasc['img'][0]['fichier'] ?? "")
            @if(file_exists($cheminfichier.DIRECTORY_SEPARATOR.$fichier))
                <img src="{{asset($cheminfichier.DIRECTORY_SEPARATOR. $tabMediasc['img'][0]['fichier'])}}" alt="" width="350" height="200" />
            @else
                <h4>Fichier inexistant!</h4>
            @endif
        </div>
    @endif
</div>