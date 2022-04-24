@if(!empty($audios))
    <div class="single-product-tab-area mg-t-15 mg-b-30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>AUDIO</h3>
                    <hr class="trait-bleu">
                </div>
                
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <div id="myTabContent1" class="tab-content">
                        @php($i = 0)
                        @foreach($audios as $r)
                            <?php
                            $i++;
                            $filelink = asset('upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR . $r['campagnetitle'] .DIRECTORY_SEPARATOR.$r['fichier']);
                            $activeIn = $i == 1 ? "active in" : "";
                            ?>
                            <div class="product-tab-list tab-pane fade {{$activeIn}}" id="single-tab{{$i}}" style="height: 250px;overflow-y: auto;">
                                <audio controls>
                                    <source src="{{$filelink}}" type="audio/ogg">
                                    <source src="{{$filelink}}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        @endforeach
                    </div>
                    <ul id="single-product-tab">
                        @php($i = 0)
                        @foreach($audios as $r)
                            <?php
                            $i++;
                            $filelink = asset('upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR . $r['campagnetitle'] .DIRECTORY_SEPARATOR.$r['fichier']);
                            $active = $i == 1 ? "active" : "";
                            ?>
                            <li class="{{$active}}">
                                <a href="#single-tab{{$i}}"  onclick="sendData('docID={{$r['id']}}','{{route ('ajax.getMediaDetail')}}','detailsAudioItem')" title="{{$r['name']}}"><i class="fa fa-headphones fa-3x"></i></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <div id="detailsAudioItem">
                        {!! \App\Http\Controllers\Administration\TableauDeBordController::getDetailMedias ($audios[0]['id']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
