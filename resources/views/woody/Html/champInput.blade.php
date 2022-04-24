<div class="form-group {{$errors->has($champ) ? ' has-error' : ''}} col-xs-6" >
          <label for=" {{$champ}} "> {{$libel}}</label>
          <input name="{{$champ}}" class="form-control" type="{{$typeInput or 'text'}}"    value="{{$value or ""}}" {{$require or ""}} placeholder="{{$placeholder or ""}}" >
                 @if($errors->has($champ))
                 <span class="help-block">
                    <strong>{{ $errors->first($champ) }}</strong>
          </span>
          @endif
</div>
