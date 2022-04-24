@extends("layouts.admin")
@section("container")
    <div class="about-sparkline mg-tb-10">
        <div class="container-fluid">
            <h3 class="main-spark-hd">GESTION DES PROFILS</h3>
            <div class="row">
                <div class="col-lg-12 col-md-10 col-sm-12">
                    <form method="post" action="{{route("traiterFormProfil")}}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="pid" value="{{$data['id'] ?? ""}}">
                        <div class="row">
                            <div class="col-sm-12">
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4 form-group">
                                        <label for="name">Nom du profil</label>
                                        <input type="text" class="form-control" name="name" id="name" required>
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label for="level">Level</label>
                                        <input type="number" step="50" min="0" class="form-control" name="level" id="level" value="{{$data['level'] ?? ""}}" required>
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label for="description">Description</label>
                                        <textarea  class="form-control" name="description" id="description">{{$data['description'] ??  ""}}</textarea>
                                    </div>
                                    <hr>
                                    {!! \App\Http\Controllers\core\FormController::champSubmit("Valider") !!}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-12 col-md-10 col-sm-12">
                    @include("woody.Tma.dataTableProfil")
                </div>
            </div>
        </div>
    </div>
@endsection
