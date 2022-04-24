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
    <div id="pageinterneitem{{$k}}" class="form-group-inner">
        <br />
        <input class="form-control" type="number" min="0" max="999" name="pageinterne" pattern="\d{1,3}" title="Un nombre entre 0 et 999" placeholder="N&deg; de la page ici" required value="{{$pub[0]['presse_page']}}">
    </div>
</div>
{!! $inputTarif !!}
