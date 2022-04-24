
@php($dateMoisForPMC = $lesDatesMoisForPM)
@php($dateForPMC = $lesDatesForPM)
<div class="scrollable">
    <table class="table-bordered">
        <caption>
        </caption>
        <thead>
            <tr>
                <th><b>Medias</b></th>
                <th><b>Insertion</b></th>
                <th><b>Budget</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataPMBySupport as $media => $tabSupports)
                @php($nb  = count($tabSupports))
                @php($cdf = count($dateForPMC))
                                      
                    @foreach ($tabSupports as $s => $t)
                            @foreach($lesDatesMoisForPM AS  $mois => $lesJours)
                                @foreach($lesJours AS $unJour)
                                    @php($bg = "")
                                    @php($nbinsertion = $dataPMByDate[$unJour][$media][$s]['insertion'] ?? 0)
                                @endforeach
                            @endforeach
                    @endforeach
                    <tr class="stotal">
                        <td class="bg-gris">
                            <b>  {{ $lesMedias[$media] }} </b>
                        </td>
                        <td class="bg-gris"><b>{{ $totalMedia[$media]['insertion'] }}</b></td>
                        <td class="bg-gris"><b>{{number_format($totalMedia[$media]['invest'], 0, ',', ' ')}}</b></td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</div>