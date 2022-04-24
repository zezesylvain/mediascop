
@php($dateMoisForPMC = $lesDatesMoisForPM)
@php($dateForPMC = $lesDatesForPM)
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
                @foreach($lesDatesMoisForPM AS $mois => $dateF)
                    <th colspan="{{ count($dateF) }}">{{ $mois }}</th>
                @endforeach
            </tr>
            <tr>
                <th><b>Medias</b></th>
                <th><b>Supports</b>  </th>
                <th><b>Insertion</b></th>
                <th><b>Budget</b></th>
                <th><b>%</b></th>
                @foreach($lesDatesMoisForPM AS  $mois => $dateF)
                    @foreach($dateF AS $jour)
                    <th style="width:5px;">
                        {{ $lesDatesForPM[$jour] }}
                    </th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($dataPMBySupport as $media => $tabSupports)
                @php($nb  = count($tabSupports))
                @php($h = $nb * 15)
                @php($cdf = count($dateForPMC))
                                      
                    @foreach ($tabSupports as $s => $t)
                        <tr>
                            <td class="tab-col-left">
                                {{ $lesMedias[$media] }}
                            </td>
                            <td>
                                @if($media != "AFFICHAGE")
                                    {!! $lesSupports[$s] !!}
                                @endif
                            </td>
                            <td>{!! $totalSupport[$s]['insertion'] !!}  </td>
                            <td>{!! number_format($totalSupport[$s]['invest'], 0, ',', ' ') !!}  </td>
                            <td>  </td>
                            
                            
                            
                            @foreach($lesDatesMoisForPM AS  $mois => $lesJours)
                                @foreach($lesJours AS $unJour)
                                    @php($bg = "")
                                    @php($nbinsertion = $dataPMByDate[$unJour][$media][$s]['insertion'] ?? 0)
                                    @if($nbinsertion)
                                        @php($bg = " background-color:#AAAAAA;")
                                    @endif
                                    <td style="width:5px;{{$bg}}">
                                        @if($nbinsertion)
                                            {{ $nbinsertion }}
                                        @endif
                                    </td>
                                @endforeach
                            @endforeach
                            
                        </tr>
                    @endforeach
                @if($nb > 1)
                    <tr class="stotal">
                        <td class="bg-gris">
                            <b>Total  {{ $lesMedias[$media] }} </b>
                        </td>
                        <td class="bg-gris"></td>
                        <td class="bg-gris"><b>{{ $totalMedia[$media]['insertion'] }}</b></td>
                        <td class="bg-gris"><b>{{number_format($totalMedia[$media]['invest'], 0, ',', ' ')}}</b></td>
                        <td class="bg-gris"></td>
                        <td colspan="{{$cdf}}">-</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>