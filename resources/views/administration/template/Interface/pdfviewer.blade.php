@extends("layouts.admin")
@section("contenu")
    {!! \App\Http\Controllers\core\TemplateController::pdfViewer ($pdf) !!}
@endsection
