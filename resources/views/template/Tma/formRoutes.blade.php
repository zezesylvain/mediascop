@extends("layouts.admin")
@section("container")
    <div class="row">
        <div class="container-fluid">
            <div class="row">
                {!! $titreHtml("Gestions  des routes",2) !!}
                <div class="col-sm-8">
                    @include("template.Tma.tableauRoutes")
                </div>
                <div class="col-sm-4">
                    <div id="estRoleItem"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
