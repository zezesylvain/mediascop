<ul class="message-menu">
@foreach($messagesRecus as $r)
    <li style="display: block!important;">
        <a href="{{route ("message.showMessage",[$r['id']])}}">
            <div class="message-img">
                {!! $chercherPhotosUser ($r['user'],'Petit') !!}
            </div>
            <div class="message-content">
                <span class="message-date">{{$date2Fr ($r['created_at'])}}</span>
                <h2>{!! $getChampTable ($getTableName ($dbTable ('DBTBL_USERS')),$r['user']) !!}</h2>
                <p>{!! $r['objet'] !!}</p>
            </div>
        </a>
    </li>
@endforeach
</ul>
