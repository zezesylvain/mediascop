<div class="form-group list-panneaux">
    <div class="bt-df-checkbox pull-left">
        <div class="row">
            <div class="col-sm-12">
                <hr class="trait-bleu">
            </div>
            @php($nbrePann = count ($panneaux))
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="searchListePanneaux">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-bottom: 8px;
">
                        <b>{{$nbrePann}}</b> panneau(x) trouvé(s)
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-bottom: 8px;">
                        <div class="i-checks pull-left">
                            <label>
                                <input type="checkbox" value="" onclick="checkboxChecker('listePanneau', this.checked,'{{$nbrePann}}')"> <i></i> Tout cochez
                            </label>
                        </div>
                    </div>
                </div>
                @php($i = 0)
                <div class="row">
                    @foreach($panneaux as $r)
                        @php($i++)
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div class="i-checks pull-left">
                                <label>
                                    <input id="listePanneau[{{$i}}]" type="checkbox" name="listePanneau[]" value="{{$r['id']}}"> <i></i> {{$r['code']}}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
