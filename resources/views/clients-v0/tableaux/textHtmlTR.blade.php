@foreach ($topNDesCampagnes as $campid => $investtotal)
    @php
        $letableauutilise = $lescampagneDetail[$campid] ;
        $nbligne = count($letableauutilise['invest']) + 1;
        $rowspan = $nbligne == 1 ? "" : " rowspan=\"$nbligne\"";
        $incTop++;
        $route = route('client.getDetail', $campid);
    @endphp
    
<tr>
    <td{{$rowspan}}>
        {{$incTop}}
    </td>
    <td{{$rowspan}}>
      {!! $letableauutilise['Annonceur'] !!}
    </td>
    <td{{$rowspan}}>
    <a target="_blank" href="{{$route}}">
        {!! $letableauutilise['Titre'] !!}
    </a>
    </td>
    @php($debut = 1)
    @foreach ($letableauutilise['insertion'] as $key => $v)
            @if ($debut > 1):
                <tr>
            @endif
                    <td>{{$key}}</td>
                    <td>{{$v}}</td>
                    <td>{{number_format($letableauutilise['invest'][$key], 0, ',', ' ')}}</td>
                </tr>

        @php($debut++)
    @endforeach

    <tr class="totalrow">
        <td>
            TOTAL
        </td>
        <td>
            {!! $letableauutilise['insertionTotal'] !!}
        </td>
        <td>
            {{number_format($letableauutilise['investTotal'], 0, ',', ' ')}}
        </td>
    </tr>
@endforeach
