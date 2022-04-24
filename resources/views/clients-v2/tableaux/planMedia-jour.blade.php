@php($dateForPM     = $lesDonnees['parItem']['date'])
@php($dateMoisForPM = $lesDonnees['dateMois'])
@php($dataForPM = $lesDonnees['planMedia'])
<table class="mb t-1 table table-striped table-bordered table-hover ">
    <thead>
        <tr>
            @foreach($dateMoisForPM AS $mois => $dateF)
                @if(count($dateF) > 1)
                    <th colspan="{{count($dateF)}}">{{$mois}}</th>
                @else
                    <th><span class="small">{{$mois}}</span></th>
                @endif
            @endforeach
        </tr>
        <tr>
            @foreach($dateForPM AS $dateF)
                <th style="width:5px;">
                    {{explode('-',$dateF)[2]}}
                </th>
            @endforeach
        </tr>
            
    </thead>
    <tbody>
    @php($cdf = count($dateForPM))
    @foreach ($dataForPM as $Ann => $tabMedia)
        @php($nb  = count($tabMedia))
        @foreach ($tabMedia as $m => $t)
            <tr style="height:15px;">
                @foreach($dateForPM AS $dateF)
                    <td style="width:5px;">
                        @if(array_key_exists($dateF, $t))
                            {{$t[$dateF]['insertion']}}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        @if($nb > 1)
            <tr>
                <td colspan="{{$cdf}}">-</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
















