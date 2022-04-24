<div class="form-horizontal">
    <select name="annee" class="form-control" onchange="sendData('periode='+this.value,'{{$route}}','PeriodeItem')">
        {!! $option !!}
    </select>
</div>
