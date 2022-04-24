@foreach ($dataTableaux['listDesAnnonceur'] as $Ann)
    <tr>
        <td class="tab-col-left">{!! $Ann !!}</td>
        <?php
             $nbIns = 0;
             $nbInv = 0;
             $investDeLAnnonceurParMedia = array_key_exists($Ann, $dataTableaux['investParAnnonceurEtParMedia']) ? $dataTableaux['investParAnnonceurEtParMedia'][$Ann] : [];
         ?>
        @foreach ($laListeDesMedias as $m)
            <?php
                $Tinsertion = array_key_exists($Ann, $dataTableaux['partDeVoixParMedia'][$m]) ? $dataTableaux['partDeVoixParMedia'][$m][$Ann] : "-";
				$Tinvest = array_key_exists($m, $investDeLAnnonceurParMedia) ? \App\Http\Controllers\Client\ReportingController::numberDisplayer($investDeLAnnonceurParMedia[$m]) : "-";
            ?>
           <td>{!! $Tinsertion !!}</td>
           <td>{!! $Tinvest !!}  </td>
        @endforeach
        @php($investAnn = array_sum($investDeLAnnonceurParMedia))
        <td>
           {!! \App\Http\Controllers\Client\ReportingController::numberDisplayer($investAnn) !!}
        </td>
        <td>
            {!! round(100 * $investAnn / $dataTableaux['investGlobalDeLaSelection'], 2)!!}
        </td>
    </tr>
@endforeach
<tr>
    <th>Total</td>
    @foreach ($laListeDesMedias as $m)
        @php
            $investMediaTotal = array_key_exists($m, $dataTableaux['investParMedia']) ? \App\Http\Controllers\Client\ReportingController:: numberDisplayer($dataTableaux['investParMedia'][$m]) : 0;
        @endphp
        <th>
            {!!  \App\Http\Controllers\Client\ReportingController:: numberDisplayer(array_sum($dataTableaux['partDeVoixParMedia'][$m]))!!}
        </th>
        <th>
            {!! $investMediaTotal ?? 0 !!}
        </th>
    @endforeach
    <td>
        {!! \App\Http\Controllers\Client\ReportingController::numberDisplayer(array_sum($dataTableaux['investParMedia'])) !!}
    </td>
    <td>
        100
    </td>
</tr>
