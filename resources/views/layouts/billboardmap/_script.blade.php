<!-- js placed at the end of the document so the pages load faster -->

<script src="{{asset("bbmap/assets/js/bootstrap.min.js")}}"></script>
<script type="text/javascript" src="{{asset("bbmap/assets/js/jquery.dcjqaccordion.2.7.js")}}"></script>
<script src="{{asset("bbmap/assets/js/jquery.scrollTo.min.js")}}"></script>
<script src="{{asset("bbmap/assets/js/jquery.sparkline.js")}}"></script>

<!--    Pour les graphic charts DEBUT-->
<!--<script src="{{asset("softs/highchart/highcharts.js")}}"></script>
<script src="{{asset("softs/highchart/highcharts-3d.js")}}"></script>
<script src="{{asset("softs/highchart/modules/exporting.js")}}"></script>

&lt;!&ndash; Pour les slider&ndash;&gt;-->
<script src="{{asset("bbmap/assets/js/responsive-slider.js")}}"></script>

<link href="{{asset("softs/jquery.datepick/css/jquery.datepick.css")}}" rel="stylesheet">
<script src="{{asset("softs/jquery.datepick/js/jquery.plugin.min.js")}}"></script>
<script src="{{asset("softs/jquery.datepick/js/jquery.datepick.js")}}"></script>
<script src="{{asset("softs/jquery.datepick/js/jquery.datepick-fr.js")}}"></script>
<script>
    $(function() {
        $('.periodeDates').datepick({rangeSelect: true,});
        $('.avantDate').datepick({maxDate: 0});
        $('.apresDate').datepick({minDate: 0});
        $('.datepickerjs').datepick({dateFormat: 'yyyy-mm-dd'});
        $('.datetimepickerjs').datepick({dateFormat: 'yyyy-mm-dd',timeFormat:  "hh:mm:ss"});

    });
</script>

<!-- data table JS
	============================================ -->

<!--<script>
    $(document).ready(function(){
        $('[data-toggle="gninatinPopover"]').popover();
    });

    $(document).ready(function() {
        $('.dataTables-listing').DataTable({
            "ordering": false,
            "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "All"]],
            "language": {
                "url": "{{asset ("softs/DataTables/frenchTranslate.json")}}"
            }
        });
    } );

</script>-->



