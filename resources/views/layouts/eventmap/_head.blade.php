    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ env('APP_NAME_EVENTMAP', 'EventMap') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="_token" content="{!! csrf_token() !!}"/>

    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset ("medias/icone.ico")}}">
    <!-- Google Fonts
		============================================ -->
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/bootstrap.min.css")}}">
    <!-- Bootstrap CSS
		============================================ -->

    <script src="{{asset("bbmap/assets/js/jquery.js")}}"></script>

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

    <link rel="stylesheet" href="{{asset ("css/flatpickr.min.css")}}">

    <link href="{{asset("bbmap/assets/css/style.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/style-default.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/css.css")}}" rel="stylesheet" type="text/css">

    <link href="{{asset("bbmap/assets/css/perso.css?d=".date('dmys')."")}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset("softs/senddata/senddata.js")}}"></script>

    <link  rel = "stylesheet"  href = "https://fonts.googleapis.com/css?family=Lato:300,400,700" >

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{asset("softs/map/cluster.js")}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
    </script>
    <style>
        .boxform{
            height: 145px !important;
            z-index: 1000;
            border-radius: 0;
        }
        fieldset .section-name{
            color: #C90000 !important;
            border-bottom: 1px rgba(201,0,0,0.7) solid !important;
            width: 100%;
            z-index: 100;
        }

        fieldset .tcocher{
            color: #000000 !important;
            border-bottom: 2px rgba(12, 12, 12, 1) solid !important;
            width: 100%;
            display: block;
            margin-bottom: 10px!important;
            /* position: absolute;
             top: 25px;*/
            z-index: 10;
        }

        fieldset {
            border: 6px rgba(201,0,0,0.2) solid !important;
            padding: 0 1.0em 1.0em 1.0em !important;
            margin: 0 0 1.2em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
        }

        legend{
            border: solid 1px #C90000 !important;
            padding: 0px 5px 0px 5px;
            border-bottom: none;
            background-color: #C90000 ;
            color: #ffffff;
            text-transform: uppercase;
        }

        legend {
            width: auto !important;
            border: none;
            font-size: 14px;
        }

        section .btn-group .btn-group{
            margin-top: 25%;
        }

        fieldset.box-com .col-sm-3{
            padding-right: 8px!important;
            padding-left: 8px!important;
        }

        .box-date select.form-control{
            padding: 1px!important;
            border-radius: 0!important;
        }
    </style>
