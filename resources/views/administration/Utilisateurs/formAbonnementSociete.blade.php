<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="all-form-element-inner">
            <form class="form-group-inner" role="form" method="POST" action="{{ route('saisie.storeOperations') }}">
                {{ csrf_field() }}
                <input type="hidden" name="user" value="{{$userID}}">
                @if($uAnnonceur)
                    <input type="hidden" name="id" value="{{$uAnn[0]['id']}}">
                @endif
                <div class="col-sm-6 form-group">
                    <div class="chosen-select-single mg-b-20">
                        <label for="annonceur">Annonceur</label>
                        <select id="annonceur" class="chosen-select form-control" name="annonceur" tabindex="-1">
                            <option value="">Choisir un annonceur</option>
                            @foreach($annonceurs as $r)
                                @php($selected = $uAnnonceur == $r['id'] ? "selected='selected'" : "")
                                <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-3" style="padding-top: 32px;">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
