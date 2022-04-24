<label for="logo">Logo</label>
<div class="file-upload-inner ts-forms">
    <div class="input prepend-big-btn">
        <label class="icon-right" for="prepend-big-btn">
            <i class="fa fa-download"></i>
        </label>
        <div class="file-button">
            Browse
            <input type="file" name="logo" id="logo" onchange="document.getElementById('prepend-big-btn').value = this.value;" value="">
        </div>
        <input type="text" id="prepend-big-btn" placeholder="no file selected">
    </div>
</div>
<input type="hidden" name="logoDel" value="">
@if ($errors->has('logo'))
    <span class="help-block">
                                    <strong>{{ $errors->first('logo') }}</strong>
                                </span>
@endif
