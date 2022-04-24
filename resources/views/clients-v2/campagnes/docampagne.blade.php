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
<div class="timeline-heading">
    <h3 class="timeline-title"> 
        Visuels de la campagne
    </h3>
</div>
@if(is_array($documents))
    @if(count($documents))
        <div class="row">
            <div class="col-xs-12 col-12 timeline-heading">
                @include('clients-v2.campagnes.menu-doc-campagne')
            </div>
            <div class="col-xs-12 col-12">    
                <div class="tab-content">      
                    @php($compteur = 1)
                    @foreach($documents as $mid => $tabMediasc)
                        <div class="tab-pane fade  @if($compteur)  active in @endif" id="media-{{ $mid }}-pills">
                            <div role="navigation">
                                <div class="col-sm-12">
                                        <div class="panel-body">
                                            @php($compteur = 0)
                                            @if(array_key_exists('img',$tabMediasc))
                                                @include('clients.campagnes.item-img')
                                            @endif
                                            @if(array_key_exists('audio', $tabMediasc))
                                                @include('clients.campagnes.item-audio')
                                            @endif
                                            @if(array_key_exists('video',$tabMediasc))
                                                @include('clients.campagnes.item-video')
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