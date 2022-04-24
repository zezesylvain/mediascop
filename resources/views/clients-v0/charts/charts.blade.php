<div class="row">
    @foreach($c3Data AS $k => $d)
        @include("clients.charts.c3ChartArea")
    @endforeach
   {{-- @foreach($c3GaugeData AS $k => $d)
       @include("clients.charts.c3ChartArea")
    @endforeach
    @foreach($camembertData AS $k => $d)
        @include("clients.charts.chartArea")
    @endforeach
    @foreach($codeData AS $k => $d)
        @include("clients.charts.codeEditorArea")
    @endforeach--}}
</div>
