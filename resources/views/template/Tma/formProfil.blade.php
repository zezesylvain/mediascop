@extends("layouts.admin")
@section("container")
    <h4>Gestion des profils</h4>
    <form method="post" action="{{route("traiterFormProfil")}}">
        {!! csrf_field() !!}
        <input type="hidden" name="pid" value="{{$data['id'] or ""}}">
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
                        <input type="number" step="50" min="0" class="form-control" name="level" id="level" value="{{$data['level'] or ""}}" required>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="description">Description</label>
                        <textarea  class="form-control" name="description" id="description">{{$data['description'] or  ""}}</textarea>
                    </div>
                    <hr>
                    {!! \App\Http\Controllers\core\FormController::champSubmit("Valider") !!}
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12">
            <hr>
            @include("template.Tma.dataTableProfil")
        </div>
    </div>
@endsection
