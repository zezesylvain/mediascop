<div class="row">
    <div class="col-sm-12">
        <hr class="trait-bleu">
        @php($nbl = count ($resultat))
        <h3>Nombre de lignes export&eacute;es: {{$nbl}} <br>
            @if($nbl)
                <a href="{{route("dashbord.getCSV")}}">Cliquez ici pour t&eacute;l&eacute;charger le fichier</a>
            @endif
        </h3>
    </div>
</div>
