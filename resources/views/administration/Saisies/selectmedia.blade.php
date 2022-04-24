<div class="row">
    @php($lesMedias = config ("constantes.medias"))
    <div class="col-xs-6 col-sm-4 col-md-3">
        <select name="media" id="media" onchange="window.location.href = this.value" data-placeholder="Choisissez un m&eacute;dia" class="chosen-select form-control" tabindex="-1">
            <option value=""></option>
            @foreach($listeDesMedia as $row)
                <?php
                $selected = $row['id'] == $media[0]['id'] ? ' selected="selected"' : '';
                ?>
                <option {{$selected}} value="{{ route ('saisie.'.strtolower ($lesMedias[$row['id']]).'')}}">{{$row['name']}}</option>
            @endforeach
        </select>
    </div>
</div>
