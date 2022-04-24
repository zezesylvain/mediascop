<form method="post" action="{{$route}}">
    {{ csrf_field() }}
    <input name="formulairedeselection" value="donnee envoyee" type="hidden">
    <div class="row">
        <div class="col-sm-2">
            <section>
                @include("clients.form.formdate")
            </section>
        </div>
        <div class="col-sm-10">
            <section>
                @include("clients.formcom")
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <section>
                <button type="button" class="btn btn-default btn-success btn-block" onclick="submit()"><i class="fa fa-check"></i>
                    Valider
                </button>
            </section>
        </div>
        <div class="col-sm-10">
            <section>
                @include("clients.formmedia")
            </section>
        </div>
    </div> <!-- END .col -->
{{--
    @include("clients.formfooter")
--}}
    <!-- END row -->
</form>
