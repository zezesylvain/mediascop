{{--<li>{!! \App\Http\Controllers\Admin\NscFunctionController::boutonBackOffice() !!}</li>--}}
<li> <a href="#"> &nbsp;&nbsp;&nbsp;&nbsp; </a></li>
{{--
<li><a href="mailto:emediascop@nsconsulting.ci" class="link-light"><i class="fa fa-phone-square"></i> Nous Contacter</a></li>
--}}
<li><a href="{{url('/logout')}}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
       title=" Se d&eacute;connecter"><i class="fa fa-unlock"></i> Se D&eacute;connecter</a>
</li>
