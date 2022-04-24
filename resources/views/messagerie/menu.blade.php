@inject('message', '\App\Http\Controllers\Messagerie\MessagerieController')
<ul class="mailbox-list">
    <li>
        <a href="{{route ('message.inbox')}}">
            <span class="pull-right">{{ $message->MessageEntrant () }}</span>
            <i class="fa fa-envelope"></i> Message entrant
        </a>
    </li>
    <li>
        <a href="{{route ('message.messagesEnvoyes')}}"><i class="fa fa-paper-plane"></i> Messages envoyés</a>
    </li>
    <li>
        <a href="#"><i class="fa fa-pencil"></i> Broullons</a>
    </li>
    <li>
        <a href="#"><i class="fa fa-trash"></i> Corbeille</a>
    </li>
</ul>
<hr>
<ul class="mailbox-list">
    <li>
        <a href="#"><i class="fa fa-gears"></i> Paramètres</a>
    </li>
    <li>
        <a href="#"><i class="fa fa-info-circle"></i> Support</a>
    </li>
</ul>
