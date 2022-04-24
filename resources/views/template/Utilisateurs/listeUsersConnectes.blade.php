<div class="notes-list-area notes-menu-scrollbar">
    <ul class="notes-menu-list">
        @foreach($users as $user)
            <li>
                <a href="#">
                    <div class="notes-list-flow">
                        <div class="notes-img">
                            <img src="{{asset ("template/img/contact/4.jpg")}}" alt="" />
                        </div>
                        <div class="notes-content">
                            <p>{!! $user['name'] !!} - {{$user['profilName']}}</p>
                            <span>Yesterday 2:45 pm</span>
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>
