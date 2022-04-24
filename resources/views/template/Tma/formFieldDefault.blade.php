<form action="{{route('storeFieldDefault')}}" method="post">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="field">CHAMPS</label>
            <input type="text" name="field" class="form-control" id="field">
        </div>
        <div class="col-md-3 form-group">
            <br>
            <label for="display" class="">
                <input type="checkbox" name="display" value="1" class="" id="display">
                DISPLAY
            </label>
        </div>
        <div class="col-md-3 form-group">
            <br>
            <label for="inline" class="checkbox-inline">
                <input type="checkbox" name="inline" value="1" class="" id="inline">
                INLINE
            </label>
        </div>
        {!! \App\Http\Controllers\core\FormController::champSubmit("Valider") !!}
    </div>
</form>
