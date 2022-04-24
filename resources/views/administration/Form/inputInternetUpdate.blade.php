<div id="internet_emplacement" class="col-xs-4 col-sm-4 col-md-4 form-group">
    <label for="internet_emplacement"> Emplacement</label>
    <select name="internet_emplacement" id="internet_emplacement" class="form-control chosen-select" required>
        <option value="">Choisir un emplacement</option>
        {!! $formOption ($dbTable ('DBTBL_INTERNET_EMPLACEMENTS','db'),'',$pub[0]['internet_emplacement'],'name') !!}
    </select>
</div>
<div id="page" class="col-xs-4 col-sm-3 col-md-3 form-group">
    <label for="presse_page"> Page</label>
    <select name="presse_page" id="presse_page" class="form-control chosen-select" required>
        <option value="">Choisir une page</option>
        {!! $formOption ($dbTable ('DBTBL_INTERNET_PAGES','db'),'',$pub[0]['presse_page'],'name') !!}
    </select>
</div>
{!! $inputSupport !!}
{!! $inputTarif !!}
