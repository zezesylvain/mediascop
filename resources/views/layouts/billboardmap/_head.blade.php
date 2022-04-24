    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{config('app.name','Mediascop')}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="_token" content="{!! csrf_token() !!}"/>

    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset ("medias/icone.ico")}}">
    <!-- Google Fonts
		============================================ -->
<!--
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
-->
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/bootstrap.min.css")}}">
    <!-- Bootstrap CSS
		============================================ -->

    <script src="{{asset("bbmap/assets/js/jquery.js")}}"></script>
    <!--<link rel="stylesheet" href="{{asset ("softs/DataTables/datatables.min.css")}}">
    <link rel="stylesheet" href="{{asset ("softs/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css")}}">
-->

    <link rel="stylesheet" href="{{asset("template/css/font-awesome.min.css")}}">

    <link href="{{asset("bbmap/assets/css/dashboard.css")}}" rel="stylesheet">
    <link href="{{asset("bbmap/assets/css/style.css")}}" rel="stylesheet">
    <link href="{{asset("bbmap/assets/css/WoodyStyle.css")}}" rel="stylesheet">
    <link href="{{asset("bbmap/assets/css/table-responsive.css")}}" rel="stylesheet">
    <link href="{{asset("bbmap/assets/css/responsive-slider.css")}}" rel="stylesheet">

    <script src="{{asset("softs/jscolor/jscolor.min.js")}}"></script>
    <!-- Custom CSS -->
    <link href="{{asset("client/index_fichiers/dashboard.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/style.css")}}" rel="stylesheet">

    <link href="{{asset("bbmap/assets/css/style.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/style-default.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/css.css")}}" rel="stylesheet" type="text/css">

    <link href="{{asset("bbmap/assets/css/perso.css?d=".date('dmys')."")}}" rel="stylesheet">
    {{--<script type="text/javascript" src="{{asset("client/index_fichiers/jquery_002.js")}}"></script>--}}


    <script type="text/javascript" src="{{asset("softs/senddata/senddata.js")}}"></script>

<!--
    <script src="{{asset ("softs/DataTables/jquery.dataTables.js")}}"></script>
    <script src="{{asset ("softs/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.js")}}"></script>
    <script src="{{asset ("softs/DataTables/datatables.min.js")}}"></script>
-->


{{--
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6cjLW8vcDWHitlFzWnWKb0QoFaLcu8xI&callback=initMap" type="text/javascript"></script>
--}}
    <link  rel = "stylesheet"  href = "https://fonts.googleapis.com/css?family=Lato:300,400,700" >

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>



    <script src="{{asset("softs/map/cluster.js")}}"></script>
{{--    <script src="{{asset("softs/map/geomap.js")}}"></script>--}}
{{--    <script src="{{asset("softs/map/map.js")}}"></script>--}}

    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
    </script>
