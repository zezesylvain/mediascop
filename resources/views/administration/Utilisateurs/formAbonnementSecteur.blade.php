<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="all-form-element-inner">
            <form class="form-group-inner" role="form" method="POST" action="{{ route('saisie.storeOperations') }}">
                {{ csrf_field() }}
                <input type="hidden" name="user" value="{{$userID}}">
                @if($uSecteur)
                    <input type="hidden" name="id" value="{{$uSect[0]['id']}}">
                @endif
                <div class="col-sm-6 form-group">
                    <div class="chosen-select-single mg-b-20">
                        <label for="secteur">Secteurs</label>
                        <select id="secteur" class="chosen-select form-control" name="secteur" tabindex="-1">
                            <option value="">Choisir un secteur</option>
                            @foreach($secteurs as $r)
                                @php($selected = $uSecteur == $r['id'] ? "selected='selected'" : "")
                                <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-3" style="padding-top: 30px;">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
