<div id="supportitem" class="col-xs-6 col-sm-4 col-md-4 col-lg-4 form-group-inner">
    <div class="chosen-select-single mg-b-20 form-group">
        <label for="support"> Support </label>
        <select id="support" name="support"  data-placeholder="" class="chosen-select form-control" tabindex="-1" {!! $optiontext !!}  required>
            <option value="">Choisir un support</option>
            {!! $formOption ($dbTable ('DBTBL_SUPPORTS','db'), '', $support, 'name', '', $cond) !!}
        </select>
    </div>
</div>
