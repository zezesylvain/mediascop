@extends("layouts.admin")
@section("contenu")
    <div class="x-editable-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="x-editable-list">
                        {!! $roles !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
  

@endsection
