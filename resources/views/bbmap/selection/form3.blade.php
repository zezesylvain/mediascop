<form method="post" action="{{route ('ajax.trouverPanneaux')}}" id="formulairedeselection">
    {{ csrf_field() }}
    <input name="formulairedeselection" value="donnee envoyee" type="hidden">
    <div class="row">
        <div class="col-xs-12 col-12 col-sm-3 col-md-2">
            <section>
                @include("bbmap.selection.formdate2")
            </section>
        </div>
        <div class="col-xs-12 col-12 col-sm-9 col-md-10">
            <section>
                @include("bbmap.selection.formcom3")
            </section>
        </div>
        <div class="col-xs-12 col-sm-12">
            <section>
                <button class="btn  btn-success btn-block" type="submit" name="valider" id="valider"><i class="fa fa-check"></i> Valider</button>
            </section>
        </div>
    </div> <!-- END .col -->
{{--
    @ include("clients.formfooter")
--}}
    <!-- END row -->
</form>
