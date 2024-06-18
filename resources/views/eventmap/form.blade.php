@if(\Illuminate\Support\Facades\Auth::check())
    <div class="">
        @include("eventmap.selection.form2")
    </div>
@endif

