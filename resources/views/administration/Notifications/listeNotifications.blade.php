<ul class="notification-menu" id="listeNotificationItem">
    @foreach($listeNotifs as $typeNotif => $notif)
        <li>
            <a href="{{ route($notif['url']) }}">
                <div class="notification-icon">
                    <i class="{{ $notif['font'] }} adminpro-checked-pro admin-check-pro" aria-hidden="true"></i>
                </div>
                <div class="notification-content">
                    <span class="notification-date">{!! \App\Helpers\FunctionController::date2Fr($notif['notification']->created_at) !!}</span>
                    <h2></h2>
                    <p>{!! $notif['message'] !!}</p>
                </div>
            </a>
        </li>
    @endforeach
</ul>
