 @inject("Session", "\Illuminate\Support\Facades\Session")
@php($dataForCamp = $Session::get("detailDesCampagnes.$cid"))
@php($dateForPMC = $dataForCamp['Date'])
@php($dateMoisForPMC = $dataForCamp['dateMois'])
@php($dateInfoForPMC = $dataForCamp['infoPM'])
@php($dataForPMC = $dataForCamp['planMedia'])

<div class="scrollable">
    <table class="table-bordered">
        <caption>
        </caption>
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                @foreach($dateMoisForPMC AS $mois => $dateF)
                    <th colspan="{{count($dateF)}}">{{$mois}}</th>
                @endforeach
            </tr>
            <tr>
                <th><b>Medias</b></th>
                <th><b>Supports</b>  </th>
                <th><b>Insertion</b></th>
                <th><b>Budget</b></th>
                <th><b>%</b></th>
                 @foreach($dateForPMC AS $dateF)
                    <th style="width:5px;">
                        {{explode('-',$dateF)[2]}}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($dataForPMC as $media => $tabSupports)
                @php($nb  = count($tabSupports))
                @php($insertion = 0)
                @php($h = $nb * 15)
                @php($invest = 0)
                @php($cdf = count($dateForPMC))
                                      
                    @foreach ($tabSupports as $s => $t)
                        <tr>
                            <td class="tab-col-left">{{$media}}</td>
                            <td>
                                @if($media != "AFFICHAGE")
                                    {!! $s !!}
                                @endif
                            </td>
                            <td>{!! $dateInfoForPMC[$s]['ins'] !!}  </td>
                            <td>{!! number_format($dateInfoForPMC[$s]['invest'], 0, ',', ' ') !!}  </td>
                            <td>  </td>
                            
                            
                            @foreach($dateForPMC AS $dateF)
                                @php($bg = "")
                                @if(array_key_exists($dateF, $t))
                                    @php($bg = " background-color:#AAAAAA;")
                                @endif
                                <td style="width:5px;{{$bg}}">
                                    @if(array_key_exists($dateF, $t))
                                        {{$t[$dateF]['ins']}}
                                    @endif
                                </td>
                                @php($insertion += $t[$dateF]['ins'] ?? 0)
                                @php($invest += $t[$dateF]['invest'] ?? 0)
                            @endforeach
                            
                        </tr>
                    @endforeach
                @if($nb > 1)
                    <tr class="stotal">
                        <td class="bg-gris"><b>Total {{$media}}</b></td>
                        <td class="bg-gris"></td>
                        <td class="bg-gris"><b>{{$insertion}}</b></td>
                        <td class="bg-gris"><b>{{number_format($invest, 0, ',', ' ')}}</b></td>
                        <td class="bg-gris"></td>
                        <td colspan="{{$cdf}}">-</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>