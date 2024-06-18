@if(\Illuminate\Support\Facades\Auth::check())
<div class="panel-group" id="accordion2">
    @include("bbmap.selection.form")
</div>
@endif

