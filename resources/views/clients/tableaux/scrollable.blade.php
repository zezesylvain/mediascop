@php($dateForPM     = $lesDonnees['lesDate'])
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
                            @php($bg = str_contains($annColor, '#') ? $annColor : "#$annColor")
                            @php($tc = str_replace('#', '', $bg))
                            @php($textColor = App\Http\Controllers\Client\PlanMediaController::GenerateInverseColor($tc))
                        @endif 
                        <tr>
                            <td class="tab-col-left" style="background-color:{{ $bg }}; color:{{ $textColor }};">{{$Ann}}</td>
                            <td>{!! $m !!}</td>
                            <td>{!! $t['ins'] !!}  </td>
                            <td>{!! number_format($t['invest'], 0, ',', ' ') !!}  </td>
                            <td>  </td>
                            
                            
                            @foreach($dateForPM AS $dateF)
                                @php($bg = "")
                                @if(array_key_exists($dateF, $t))
                                    @if(array_key_exists($Ann, $listDesCouleursDesAnnonceur))
                                        @php($annColor = $listDesCouleursDesAnnonceur[$Ann])
                                        @php($bg = str_contains($annColor, '#') ? $annColor : "#$annColor")
                                        @php($tc = str_replace('#', '', $bg))
                                        @php($textColor = App\Http\Controllers\Client\PlanMediaController::GenerateInverseColor($tc))
                                    @endif
                                @endif
                                <td style="width:5px; background-color:{{ $bg }}; color:{{ $textColor }};">
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
<script>
    function invertColor(hex, bw) {
    if (hex.indexOf('#') === 0) {
        hex = hex.slice(1);
    }
    // convert 3-digit hex to 6-digits.
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    if (hex.length !== 6) {
        throw new Error('Invalid HEX color.');
    }
    var r = parseInt(hex.slice(0, 2), 16),
        g = parseInt(hex.slice(2, 4), 16),
        b = parseInt(hex.slice(4, 6), 16);
    if (bw) {
        // https://stackoverflow.com/a/3943023/112731
        return (r * 0.299 + g * 0.587 + b * 0.114) > 186
            ? '#000000'
            : '#FFFFFF';
    }
    // invert color components
    r = (255 - r).toString(16);
    g = (255 - g).toString(16);
    b = (255 - b).toString(16);
    // pad each with zeros and return
    return "#" + padZero(r) + padZero(g) + padZero(b);
}
    
    function padZero(str, len) {
        len = len || 2;
        var zeros = new Array(len).join('0');
        return (zeros + str).slice(-len);
    }
</script>