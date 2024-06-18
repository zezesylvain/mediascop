<form method="post" action="{{route ('ajax.trouverPanneaux')}}" id="formulairedeselection">
    {{ csrf_field() }}
    <input name="formulairedeselection" value="donnee envoyee" type="hidden">
    <div class="row">
        <div class="col-sm-12">
            <section>
                @include("bbmap.selection.formdate")
            </section>
        </div>
        <div class="col-sm-12">
            <section>
                @include("bbmap.selection.formcom")
            </section>
        </div>
        <div class="col-sm-12">
            <section>
                @include("bbmap.selection.formlocalisation")
            </section>
        </div>
        <div class="col-sm-12">
            <section>
                @include("bbmap.selection.formfooter")
            </section>
        </div>
    </div> <!-- END .col -->
{{--
    @include("clients.formfooter")
--}}
    <!-- END row -->
</form>
