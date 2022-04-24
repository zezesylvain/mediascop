<div class="col-xs-2 col-sm-3">
    <select class="form-control" name="jour{{$num}}" onchange="sendData('param=jour{{$num}}&valeur=' +this.value+'&action=date', '{{route('ajax.reporting')}}', 'campagneitemlist')">
        {!! $opselectjour !!}
    </select>
</div>
<div class="col-xs-5 col-sm-5">
    <select class="form-control" name="mois{{$num}}" onchange="sendData('param=mois{{$num}}&valeur=' +this.value+'&action=date', '{{route('ajax.reporting')}}', 'campagneitemlist')">
        {!! $opselectmois !!}
    </select>
</div>
<div class="col-xs-5 col-sm-4">
    <select class="form-control" name="annee{{$num}}" onchange="sendData('param=annee{{$num}}&valeur=' +this.value+'&action=date', '{{route('ajax.reporting')}}', 'campagneitemlist')">
        {!! $opselectyear !!}
    </select>
</div>
