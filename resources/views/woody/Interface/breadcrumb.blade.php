<ul class="breadcome-menu">
    @php($slash = "<span class=\"bread-slash\">/</span>")
    @php($i = 0)
    @foreach($uri as $r)
        @php($i++)
        <li>
            <a href="{{url($route)}}">{{$r}}</a>
            @if($i < count ($uri))
                {!! $slash !!}
            @endif
        </li>
    @endforeach
</ul>
