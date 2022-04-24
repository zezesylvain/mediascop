{!! $inputSupport !!}
<div class="col-xs-4 col-sm-3 col-md-2">
    <div class="chosen-select-single mg-b-20 form-group-inner">
        <label for="page">Page</label>
        <select data-placeholder="" class="chosen-select form-control" tabindex="-1" name="presse_page" id="page" onchange="sendData('k={{$k}}&lapage=' + this.value+'&action={{$action}}', '{{$route}}', 'pageinterneitem{{$k}}')" required>
            <option value="">---</option>
            <option value="1">Première ou dernière page</option>
            <option value="Page interne">Page interne</option>
        </select>
    </div>
    <div id="pageinterneitem{{$k}}"> </div>
</div>
{!! $inputTarif !!}

