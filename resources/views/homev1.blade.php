@extends("layouts.admin")
@section("contenu")
    <div class="section-admin container-fluid" style="margin-top: 50px;">
        <div class="row admin text-center">
            <div class="col-md-12">
                @include("administration.Indicateurs.widget")
            </div>
        </div>
    </div>
@endsection
@section("breadcome-area")
    @includeIf("layouts.administration._breadcome_area")
@endsection
