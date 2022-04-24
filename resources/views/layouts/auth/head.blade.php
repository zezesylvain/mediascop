<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>{{config('app.name','Mediascop')}}</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{!! csrf_token() !!}"/>

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
<link rel="stylesheet" href="{{asset("template/css/morrisjs/morris.css")}}">
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
<!-- forms CSS
	============================================ -->
<link rel="stylesheet" href="{{asset("template/css/form/all-type-forms.css")}}">
<!-- style CSS
    ============================================ -->
<link rel="stylesheet" href="{{asset("template/style.css")}}">
<!-- responsive CSS
    ============================================ -->
<link rel="stylesheet" href="{{asset("template/css/responsive.css")}}">
<!-- modernizr JS
    ============================================ -->
<script src="{{asset("template/js/vendor/jquery-1.11.3.min.js")}}"></script>

<script src="{{asset("template/js/vendor/modernizr-2.8.3.min.js")}}"></script>

{{----}}
<script type="text/javascript">
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
    });
</script>
{{----}}
