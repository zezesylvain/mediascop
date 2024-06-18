<!-- js placed at the end of the document so the pages load faster -->
<script src="{{asset("client/index_fichiers/jquery_004.js")}}"></script>
<script src="{{asset("client/index_fichiers/bootstrap.js")}}"></script>
<script src="{{asset("client/index_fichiers/jquery_006.js")}}"></script>
<script src="{{asset("client/index_fichiers/jquery_003.js")}}"></script>
<script src="{{asset("client/index_fichiers/dataTables.js")}}"></script>

<!-- chosen JS
	============================================ -->
<script src="{{asset ("template/js/chosen/chosen.jquery.js")}}"></script>
<script src="{{asset ("template/js/chosen/chosen-active.js")}}"></script>

<script src="{{ asset('js/flatpickr.min.js') }}"></script>
<script src="{{ asset('js/flatpickrlangfr.js') }}"></script>

{{--
<link href="{{asset("softs/jquery.datepick/css/jquery.datepick.css")}}" rel="stylesheet">
<script src="{{asset("softs/jquery.datepick/js/jquery.plugin.min.js")}}"></script>
<script src="{{asset("softs/jquery.datepick/js/jquery.datepick.js")}}"></script>
<script src="{{asset("softs/jquery.datepick/js/jquery.datepick-fr.js")}}"></script>
--}}
<script>
    $( function() {
        $("#startPicker,#endPicker").flatpickr(
            {
                dateFormat : 'Y-m-d',
                altFormat : 'j/m/Y',
                altInput : true,
                allowInput : true,
                locale : 'fr'
            }
        );
    } );
</script>

{{--<script>
    $(function() {
        $('.periodeDates').datepick({rangeSelect: true,});
        $('.avantDate').datepick({maxDate: 0});
        $('.apresDate').datepick({minDate: 0});
        $('.datepickerjs').datepick({dateFormat: 'yyyy-mm-dd'});
        $('.datetimepickerjs').datepick({dateFormat: 'yyyy-mm-dd'});
        
        $('#startPicker,#endPicker').datepick({
            onSelect: customRange, showTrigger: '#calImg',dateFormat:'yyyy-mm-dd'});
    
        function customRange(dates) {
            var key = '';
            if (this.id == 'startPicker') {
                key = 'date_debut';
                $('#endPicker').datepick('option', 'minDate', dates[0] || null);
            }
            else {
                key = 'date_fin';
                $('#startPicker').datepick('option', 'maxDate', dates[0] || null);
            }
            var dformat = $('#'+this.id).datepick({dateFormat:'yyyy-mm-dd'}).val();
            
            changerLaDate(dformat,key);
        }
        
        function changerLaDate(ladate,key) {
            return changeDate(ladate,key);
        }
    });
</script>--}}

<script>
    $(document).ready(function () {
        $('.dataTables-listing').dataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
<!--    Pour les graphic charts DEBUT-->
<script src="{{asset("client/index_fichiers/highcharts.js")}}"></script>
<script src="{{asset("client/index_fichiers/highcharts-3d.js")}}"></script>
<script src="{{asset("client/index_fichiers/exporting.js")}}"></script>
<!--        Slider dans detail campagne -->
<script src="{{asset("client/index_fichiers/jquery_005.js")}}"></script>
<script src="{{asset("client/index_fichiers/responsive-slider.js")}}"></script>
<!--    FIN    Slider dans detail campagne -->

<!-- c3 JS ============== -->
<script src="{{asset ("template/js/c3-charts/d3.min.js")}}"></script>
<script src="{{asset ("template/js/c3-charts/c3.min.js")}}"></script>
<script src="{{asset ("softs/saveSvgAsPngJs/saveSvgAsPng.js")}}"></script>
@include("clients.charts.c3Js")
