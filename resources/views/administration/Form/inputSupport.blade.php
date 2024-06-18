
<div id="supportitem" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group-inner">
    @php
        $support = session ()->has ("supportPubIn")  ? session ()->get ("supportPubIn") : 0;
    @endphp
    <div class="chosen-select-single mg-b-20 form-group-inner">
        <label for="support"> Support </label>
        <select id="support" name="support"  data-placeholder="" class="chosen-select form-control" tabindex="-1" {!! $optiontext !!}  required>
            <option value="">Choisir un support</option>
            {!! $formOption ($dbTable ('DBTBL_SUPPORTS','db'), '', $support, 'name', '', $cond) !!}
        </select>
    </div>
</div>
