<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="VZ">
    
    <title>{{ env('APP_NAME_REPORTING', 'Repporting') }}</title>
    
    <meta name="_token" content="{!! csrf_token() !!}"/>
    
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="https://www.mediascop.net/medias/icone.png">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css' />
    
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/bootstrap.min.css")}}">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset("template/css/font-awesome.min.css")}}">
    <!-- Custom CSS -->
    <link href="{{asset("client/index_fichiers/dashboard.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/style.css")}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset ("css/flatpickr.min.css")}}">

    <script type="text/javascript" src="{{asset("client/index_fichiers/jquery-1.js")}}"></script>
    <script src="{{asset("client/index_fichiers/jquery.js")}}"></script>
    <link href="{{asset("client/index_fichiers/dataTables.css")}}" rel="stylesheet">
    
    <!-- Chart CSS==================== -->
    <link rel="stylesheet" href="{{asset ("template/css/c3/c3.min.css")}}">
    <!-- chosen CSS
	============================================ -->
    <link rel="stylesheet" href="{{asset ("template/css/chosen/bootstrap-chosen.css")}}">

    <link href="{{asset("client/index_fichiers/table-responsive.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/responsive-slider.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/style-default.css")}}" rel="stylesheet">
    <link href="{{asset("client/index_fichiers/perso.css?d=".date('dmys')."")}}" rel="stylesheet">
    <!-- GOOGLE FONTS-->
    <link href="{{asset("client/index_fichiers/css.css")}}" rel="stylesheet" type="text/css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--    Pour les graphic charts DEBUT-->
    <script type="text/javascript" src="{{asset("client/index_fichiers/jquery_002.js")}}"></script>
    <script type="text/javascript" src="{{asset("softs/senddata/senddata.js")}}"></script>

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
    

    <link rel="stylesheet" href="{{asset("css/vz.css")}}?d={{date('YmdHis')}}">
    <link rel="stylesheet" href="{{asset("css/style-perso.css")}}?d={{date('YmdHis')}}">
</head>
