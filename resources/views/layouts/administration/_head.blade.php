<head>
    
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{config('app.name','Mediascop')}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet"> 

    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset ("medias/icone.ico")}}">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/bootstrap.min.css")}}">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/font-awesome.min.css")}}">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/owl.carousel.css")}}">
    <link rel="stylesheet" href="{{asset("template/css/owl.theme.css")}}">
    <link rel="stylesheet" href="{{asset("template/css/owl.transitions.css")}}">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/animate.css")}}">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/normalize.css")}}">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/meanmenu.min.css")}}">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/main.css")}}">
    <!-- morrisjs CSS
		============================================ -->
    {{--<link rel="stylesheet" href="{{asset("template/css/morrisjs/morris.css")}}">--}}
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/scrollbar/jquery.mCustomScrollbar.min.css")}}">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/metisMenu/metisMenu.min.css")}}">
    <link rel="stylesheet" href="{{asset("template/css/metisMenu/metisMenu-vertical.css")}}">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/calendar/fullcalendar.min.css")}}">
    <link rel="stylesheet" href="{{asset("template/css/calendar/fullcalendar.print.min.css")}}">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/alerts.css")}}">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/data-table/bootstrap-table.css")}}">
    <link rel="stylesheet" href="{{asset ("template/css/data-table/bootstrap-editable.css")}}">
    
    <!-- x-editor CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/editor/select2.css")}}">
    {{--<link rel="stylesheet" href="{{asset("template/css/editor/datetimepicker.css")}}">--}}
    <link rel="stylesheet" href="{{asset("template/css/editor/bootstrap-editable.css")}}">
    <link rel="stylesheet" href="{{asset("template/css/editor/x-editor-style.css")}}">
    <!-- modals CSS
	   ============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/modals.css")}}">
    <!-- touchspin CSS
		============================================ -->
    
    <link rel="stylesheet" href="{{asset ("template/css/touchspin/jquery.bootstrap-touchspin.min.css")}}">
    <!-- datapicker CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/datapicker/datepicker3.css")}}">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/form/themesaller-forms.css")}}">
    <!-- colorpicker CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/colorpicker/colorpicker.css")}}">
    <!-- select2 CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/select2/select2.min.css")}}">
    
    <!-- chosen CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/chosen/bootstrap-chosen.css")}}">
    <!-- ionRangeSlider CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/ionRangeSlider/ion.rangeSlider.css")}}">
    <link rel="stylesheet" href="{{asset ("template/css/ionRangeSlider/ion.rangeSlider.skinFlat.css")}}">
    
    <!-- datapicker CSS
	============================================ -->
    {{--<link rel="stylesheet" href="{{asset ("template/css/datapicker/datepicker3.css")}}">--}}
    <link rel="stylesheet" href="{{asset ("css/flatpickr.min.css")}}">

    <!-- duallistbox CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/duallistbox/bootstrap-duallistbox.min.css")}}">
    
    <!-- dropzone CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/dropzone/dropzone.css")}}">
    
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/responsive.css")}}">
    
    <!-- modernizr JS
		============================================ -->
    <script src="{{asset("template/js/vendor/jquery-1.11.3.min.js")}}"></script>
    <script src="{{asset("template/js/vendor/modernizr-2.8.3.min.js")}}"></script>
    <!-- forms CSS
			============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/form/all-type-forms.css")}}">
    
    <!-- style CSS
	============================================ -->
    <link rel="stylesheet" href="{{asset("template/style.css")}}">
    
    {{--<link rel="stylesheet" href="{{asset("softs/DataTables/datatables.css")}}">--}}
    <link href="{{asset("softs/dataTables11/dataTables.bootstrap.css")}}" rel="stylesheet" />
    
    <link rel="stylesheet" href="{{asset("softs/summernote-master/dist/summernote.css")}}">
    
    <script src="{{asset ("softs/jscolor/jscolor.js")}}"></script>
    <link rel="stylesheet" href="{{asset ("softs/Notification-41mag/UIKit.css")}}">
    <script src="{{asset ("softs/Notification-41mag/UIKit.js")}}"></script>
    
    <link rel="stylesheet" href="{{asset("softs/css/perso.css?d=".date('dmys')."")}}">
    <!-- SendData Ajax
	============================================ -->
    <script src="{{asset("softs/senddata/senddata.js")}}"></script>
    
    {{--@if(isset($map))--}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6cjLW8vcDWHitlFzWnWKb0QoFaLcu8xI&callback=initialize"></script>
    <script src="{{ asset('js/aspnetcdn.jquery1.10.1.min.js') }}"></script>
    <script src="{{asset("softs/map/cluster.js")}}" ></script>
    <script src="{{asset("softs/map/geomap.js")}}"></script>
	{{--@endif--}}

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
            });
            $.ajaxSetup({
                headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
            });
        });
    </script>
    <link rel="stylesheet" href="{{asset("css/vz.css")}}?d={{date('Ymdhis')}}">
    
    <style>
        @media (max-width: 767px) {
            .all-content-wrapper {
            margin-left: 0px;
        }
           .header-top-area{
               position: relative;
               left: 0px;
           }
            .logo-pro {
                display: block;
                text-align: center;
                background: #fff;
                font-family: 'Montserrat', sans-serif;
            }
        }
        .sidebar-nav .metismenu ul a {
        padding: 1px 15px 1px 30px;
            padding-right: 15px;
    }
    </style>
</head>
