    <label for="code">Code</label>
    <input id="code" placeholder="" type="text" class="form-control" name="code" value="{{ $code }}"  autofocus>
    @if ($errors->has('code'))
        <span class="help-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
    @endif
