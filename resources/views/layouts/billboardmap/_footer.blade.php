<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
<footer class="row">
    <div class="col-xs-12">
        <hr>
    </div>
</footer>
