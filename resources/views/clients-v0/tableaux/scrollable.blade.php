@php($dateForPM     = $lesDonnees['parItem']['date'])
@php($dateMoisForPM = $lesDonnees['dateMois'])
@php($dataForPM = $lesDonnees['planMedia'])

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
                @foreach($dateMoisForPM AS $mois => $dateF)
                    <th colspan="{{count($dateF)}}">{{$mois}}</th>
                @endforeach
            </tr>
            <tr>
                <th><b>Annonceurs</b></th>
                <th><b>Media</b>  </th>
                <th><b>Insertion</b></th>
                <th><b>Budget</b></th>
                <th><b>%</b></th>
                 @foreach($dateForPM AS $dateF)
                    <th style="width:5px;">
                        {{explode('-',$dateF)[2]}}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($dataForPM as $Ann => $tabMedia)
                @php($nb  = count($tabMedia))
                @php($insertion = 0)
                @php($h = $nb * 15)
                @php($invest = 0)
                @php($cdf = count($dateForPM))
                                      
                    @foreach ($tabMedia as $m => $t)
                        @php($bg = "")
                        @if(array_key_exists($Ann, $listDesCouleursDesAnnonceur))
                            @php($annColor = $listDesCouleursDesAnnonceur[$Ann])
                            @php($bg = "background-color:$annColor;")
                        @endif 
                        <tr>
                            <td class="tab-col-left" style="{{$bg}}">{{$Ann}}</td>
                            <td>{!! $m !!}</td>
                            <td>{!! $t['ins'] !!}  </td>
                            <td>{!! number_format($t['invest'], 0, ',', ' ') !!}  </td>
                            <td>  </td>
                            
                            
                            @foreach($dateForPM AS $dateF)
                                @php($bg = "")
                                @if(array_key_exists($dateF, $t))
                                    @if(array_key_exists($Ann, $listDesCouleursDesAnnonceur))
                                        @php($annColor = $listDesCouleursDesAnnonceur[$Ann])
                                        @php($bg = " background-color:$annColor;")
                                    @endif
                                @endif
                                <td style="width:5px;{{$bg}}">
                                    @if(array_key_exists($dateF, $t))
                                        {{$t[$dateF]['insertion']}}
                                    @endif
                                </td>
                            @endforeach
                            
                        </tr>
                        @php($insertion += $t['ins'])
                        @php($invest += $t['invest'])
                    @endforeach
                @if($nb > 1)
                    <tr class="stotal">
                        <td class="bg-gris"><b>Total {{$Ann}}</b></td>
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