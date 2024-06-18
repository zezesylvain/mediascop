@php
    $nbreNotif = \App\Http\Controllers\Messagerie\MessagerieController::nbreNotifications();
    $cssBulle = '';
    if ($nbreNotif > 0):
        $cssBulle = 'link-notif-bulle';
    endif;
@endphp
@if ($nbreNotif > 0)
    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" title="Vous avez {{ $nbreNotif }} notification(s)." id="linkNotifBulle" class="nav-link {{ $cssBulle }} dropdown-toggle">
        <i class="fa fa-bell-o" aria-hidden="true"></i>
        <span class="indicator-ntt" id="nbreNotifItem">@if($nbreNotif > 0){!! $nbreNotif !!}@endif</span>
    </a>
    <div role="menu" class="notification-author dropdown-menu animated zoomIn">
        <div class="notification-single-top">
            <h1>Notifications</h1>
        </div>
        {!! \App\Http\Controllers\Messagerie\MessagerieController::showNotification() !!}
        <div class="notification-view">
            <a href="#">Voir toutes les Notifications</a>
        </div>
    </div>
    <input type="hidden" name="notifCompteur" id="notifCompteurItem" value="{{ $nbreNotif }}">
    
    <script>
        function listNotification() {
            var url = "{{route ('ajax.checkNotification')}}";
            $.ajax({
                type : "POST",
                url : url ,
                data : {
                    param : 'check',
                },
                dataType: "JSON",
                success : function(result){
                    let notifCpt = result.notifs;
                    let inputCpt = parseInt($('#notifCompteurItem').val(),10);
                    if (notifCpt) {
                        $('#linkNotifBulle span').css('display','flex').attr('title','Vous avez '+notifCpt+' notification(s).');
                        $('#nbreNotifItem').text(notifCpt);
                        $('#notifCompteurItem').val(notifCpt)
                        $('#listeNotificationItem').html(result.listeNotif);
                        if(notifCpt > inputCpt){
                            $('#notifCompteurItem').val(notifCpt);
                            mySoNiceSound('{{asset("softs/notificationSonore/0450.mp3")}}');
                        }
                    }else {
                        $('#nbreNotifItem').text(result.notifs);
                        $('#linkNotifBulle span').css('display','none')
                    }
                }
            });
        }
    
        setInterval(function () {
            listNotification();
            getNotification();
        },30000);
    
        function mySoNiceSound(s)
        {
            const e = document.createElement('audio');
            e.setAttribute('src',s);
            e.play();
        }
    
    </script>
@endif    
