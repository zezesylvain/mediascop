<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa fa-envelope-o adminpro-chat-pro" aria-hidden="true"></i>
    @php($mess = new \App\Http\Controllers\Messagerie\MessagerieController)
    @if($mess->verifierSiMessageNonLue ())
        <span id="indicator-ms" class="indicator-ms"></span>
    @else
        <span id="indicator-ms" class=""></span>
    @endif
</a>
<div role="menu" class="author-message-top dropdown-menu animated zoomIn">
    <div class="message-single-top">
        <h1>Message</h1>
    </div>
    {!! $messageNotifier () !!}
    <div class="message-view">
        <a href="{{route ('message.inbox')}}">Consulter Tous les messages</a>
    </div>
</div>

<script>
    function getNotification() {
        let url = "{{route ('ajax.checkMessage')}}";
        $.ajax({
            type : "POST",
            url : url ,
            data : {
                param : 'check',
            },
            dataType: "JSON",
            success : function(result){
                if (result.notification) {
                    $('#indicator-ms').css('display','block').addClass("indicator-ms");
                    //mySoNiceSound('{{public_path ("softs/notificationSonore/0450.mp3")}}');
                }else {
                    $('#indicator-ms').css('display','none')
                }
            }
        });
    }

/*
    setInterval(function () {
        getNotification();
    },30000);
*/

</script>
