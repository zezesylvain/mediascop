<?php /*/?>
<li>
    <a href="#" class="link-light" title=" Comment &ccedil;a marche">
        <i class="fa fa-question-circle fa-2x"></i>
    </a>
</li>
<li>
    <a href="#" class="link-light" title="Glossaire">
        <i class="fa fa-book fa-2x"></i>
    </a>
</li>
<li>
    <a href="#" class="link-light" title="Contact">
        <i class="fa fa-phone-square fa-2x"></i>
    </a>
</li>
<li>
    <a href="#" class="link-support"  data-toggle="modal" data-target="#bdc-modal" title="Base de connaissance">
        <i class="fa fa-database fa-2x"></i>
    </a>
</li>
<li>
    <a href="#" class="link-support"  data-toggle="modal" data-target="#myModal" title="Support">
        <i class="fa fa-support fa-2x"></i>
    </a>
</li>

<?php //*/?>
<li>
    <a href="{{url('/logout')}}"  onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
       title=" Se d&eacute;connecter">
        <i class="fa fa-lock fa-2x"></i>
    </a>
</li>
