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
@dd($lesMedias, $documents)
@if(is_array($documents))
    @if(count($documents))
        <div class="row">
            <div class="col-xs-12 col-12">
                <div class="timeline-heading">
                    <h3 class="timeline-title"> 
                        @include('clients.campagnes.menu-doc-campagne')
                    </h3>
                </div>
                
            </div>
            <div class="col-xs-12 col-12">    
                <div class="tab-content">        
                    @php($compteur = 1)
                    @foreach($documents as $mid => $tabMediasc)
                        <div class="tab-pane fade @if($compteur)  active in @endif" id="media-{{ $mid }}-pills">
                            @php($compteur = 0)
                            <div role="navigation">
                                <div class="col-sm-12"> 
                                    <div class="row">
                                        @if(array_key_exists('img',$tabMediasc))
                                            <div class="col-sm-12">
                                                <div class="panel panel-info">
                                                    <div class="panel-body">
                                                        @if(count($tabMediasc['img']) > 1)
                                                            <div class="text-center slideshow img img-responsive">
                                                                <ul>
                                                                    @foreach($tabMediasc['img'] as $doc)
                                                                        @php($fichier = $cheminfichier.DIRECTORY_SEPARATOR.$doc['fichier'])
                                                                        @if(file_exists($fichier))
                                                                            <li>
                                                                                <img src="{{asset($fichier)}}" alt="" width="350" height="200" />
                                                                            </li>
                                                                        @else
                                                                            <h4>Fichier inexistant!</h4>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <div class="text-center img img-responsive">
                                                                @php($fichier = $tabMediasc['img'][0]['fichier'] ?? "")
                                                                @if(file_exists($cheminfichier.DIRECTORY_SEPARATOR.$fichier))
                                                                    <img src="{{asset($cheminfichier.DIRECTORY_SEPARATOR. $tabMediasc['img'][0]['fichier'])}}" alt="" width="350" height="200" />
                                                                @else
                                                                    <h4>Fichier inexistant!</h4>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if(array_key_exists('audio', $tabMediasc))
                                            <div class="col-sm-12">
                                                <hr>
                                                <div class="panel panel-info">
                                                    <div class="panel-body">
                                                        @foreach($tabMediasc['audio'] as $r)
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
                                        @if(array_key_exists('video',$tabMediasc))
                                            <div class="col-sm-12">
                                                <div class="panel panel-info
                                                    <div class="panel-body">
                                                        @foreach($tabMediasc['video'] as $r)
                                                            <div class="col-sm-4">
                                                                <video controls src="{{asset($cheminfichier.DIRECTORY_SEPARATOR.$r['fichier'])}}">Ici la description alternative</video>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function(){
                setInterval(function(){
                    $(".slideshow ul").animate({marginLeft:-350},800,function(){
                        $(this).css({marginLeft:0}).find("li:last").after($(this).find("li:first"));
                    })
                }, 10500);
            });
        </script>
    @endif
@endif