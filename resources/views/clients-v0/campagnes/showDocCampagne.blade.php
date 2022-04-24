<style>
    .slideshow {
        width: 350px;
        height: 200px;
        overflow: hidden;
        border: 3px solid #F2F2F2;
        text-align: center;
    }

    .slideshow ul {
        /* 4 images donc 4 x 100% */
        width: 400%;
        height: 200px;
        padding:0; margin:0;
        list-style: none;
    }
    .slideshow li {
        float: left;
    }
</style>
@if(count($medias))
    <div class="row">
        @foreach($medias as $mid => $mediaName)
            <div class="col-sm-12">
                @if(array_key_exists($mediaName,$documents))
                    <hr>
                    <div class="row">
                        @if(array_key_exists('img',$documents[$mediaName]))
                            <div class="col-sm-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">{{$mediaName}}</div>
                                    <div class="panel-body">
                                        @if(count($documents[$mediaName]['img']) > 1)
                                            <div class="slideshow img img-responsive text-center">
                                                <ul>
                                                    @foreach($documents[$mediaName]['img'] as $doc)
                                                        @if(file_exists($cheminfichier.DIRECTORY_SEPARATOR.$doc['fichier']))
                                                            <li>
                                                                <img src="{{asset($cheminfichier.DIRECTORY_SEPARATOR.$doc['fichier'])}}" alt="" width="350" height="200" />
                                                            </li>
                                                        @else
                                                            <h4>Fichier inexistant!</h4>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div class="img img-responsive text-center">
                                                @php($fichier = [$mediaName]['img'][0]['fichier'] ?? "")
                                                @if(file_exists($cheminfichier.DIRECTORY_SEPARATOR.$fichier))
                                                    <img src="{{asset($cheminfichier.DIRECTORY_SEPARATOR.$documents[$mediaName]['img'][0]['fichier'])}}" alt="" width="350" height="200" />
                                                @else
                                                     <h4>Fichier inexistant!</h4>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(array_key_exists('audio',$documents[$mediaName]))
                            <div class="col-sm-12">
                                <hr>
                                <div class="panel panel-info">
                                    <div class="panel-heading">{{$mediaName}}</div>
                                    <div class="panel-body">
                                        @foreach($documents[$mediaName]['audio'] as $r)
                                            <div class="col-sm-4">
                                                @if(file_exists($cheminfichier.DIRECTORY_SEPARATOR.$r['fichier']))
                                                    <figure>
                                                        <figcaption>Listen to the T-Rex:</figcaption>
                                                        <audio controls>
                                                            <source src="{{asset($cheminfichier.DIRECTORY_SEPARATOR.$r['fichier'])}}" type="audio/mpeg">

                                                            Your browser does not support the
                                                            <code>audio</code> element.
                                                        </audio>
                                                    </figure>
                                                @else
                                                    <h3>Fichier inexistant!</h3>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(array_key_exists('video',$documents[$mediaName]))
                            <div class="col-sm-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">{{$mediaName}}</div>
                                    <div class="panel-body">
                                        @foreach($documents[$mediaName]['video'] as $r)
                                            <div class="col-sm-4">
                                                <video controls src="{{asset($cheminfichier.DIRECTORY_SEPARATOR.$r['fichier'])}}">Ici la description alternative</video>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif
<script type="text/javascript">
    $(function(){
        setInterval(function(){
            $(".slideshow ul").animate({marginLeft:-350},800,function(){
                $(this).css({marginLeft:0}).find("li:last").after($(this).find("li:first"));
            })
        }, 10500);
    });
</script>