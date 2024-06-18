
@isset($selection)
    <div class="row">
        <div class="col-sm-12">
            <p>
                @if(array_key_exists ("Periode",$selection))
                      <strong>Période : </strong><span>Du {{ $selection['Periode']['Debut'].' Au '.$selection['Periode']['Fin'] }}</span><br>
                @endif
                @if(array_key_exists ("Secteur",$selection))
                      <strong>Secteur(s): </strong><span>{{implode(',',$selection['Secteur'])}}</span><br>
                @endif
                @if(array_key_exists ("Annonceur",$selection))
                      <strong>Annonceur(s): </strong><span>{{implode(',',$selection['Annonceur'])}}</span><br>
                @endif
                @if(array_key_exists ("Localite",$selection))
                      <strong>Localisation: </strong><span>{{implode(',',$selection['Localite'])}};</span><br>
                @endif
                @if(array_key_exists ("Format",$selection))
                      <strong>Format(s): </strong><span>{{implode(',',$selection['Format'])}};</span>
                @endif
            </p>
        </div>
    </div>
@endisset
