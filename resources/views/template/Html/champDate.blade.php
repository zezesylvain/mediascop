<?php
    $datejour = $value != "" ? $value : $datejour;
?>
<input type="hidden" id="DPC_TODAY_TEXT" value="Aujourd'hui">
<input type="hidden" id="DPC_FIRST_WEEK_DAY" value="1">
<input type="hidden" id="DPC_BUTTON_TITLE" value="Calendrier">
<input type="hidden" id="DPC_MONTH_NAMES" value="['Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre']">
<input type="hidden" id="DPC_DAY_NAMES" value="['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']">

<div class="form-group {{$errors->has($champ) ? ' has-error' : ''}} col-xs-6" >
    <label for=" {{$champ}} "> {{$libel}}</label>
    <input name="{{$champ}}" size="10"  type="text" class="form-control popupDatepicker"  id="DPC_dddebutfop_YYYY-MM-DD{{$champ}}"  value="{{$datejour}}">
    @if($errors->has($champ))
        <span class="help-block">
            <strong>{{ $errors->first($champ) }}</strong>
        </span>
    @endif
</div>