@inject("report", "\App\Http\Controllers\Client\ReportingController")
<div class="col-xs-12 bordered">
    <div class="row">
        <div class="col-xs-12">
            <div class="content-panel">
                <h4>
                    <i class="fa fa-angle-right"></i>
                    Insertion et investissement des annonceurs par Media
                </h4>
                <section id="unseen">
                    <table class="table table-bordered table-too-long tableau-tabcol">
                        <thead>
                        <tr>
                            <th class="couleur_th"></th>
                            @foreach ($laListeDesMedias as $m)
                                <th colspan="2"> {!! $m !!} </th>
                            @endforeach
                            <th class="couleur_th">TOTAL</th>
                            <th class="couleur_th">%</th>
                        </tr>
                        <tr>
                            <th class="couleur_th"></th>
                            @foreach ($laListeDesMedias as $m)
                                <th> ins </th>
                                <th> invest </th>
                            @endforeach
                            <th class="couleur_th"> Invest </th>
                            <th class="couleur_th"> </th>
                        </tr>
                        </thead>
                        <tbody>
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
                                    $Tinvest = array_key_exists($m, $investDeLAnnonceurParMedia) ? $report->numberDisplayer($investDeLAnnonceurParMedia[$m]) : "-";
                                    ?>
                                    <td>{!! $Tinsertion !!}</td>
                                    <td>{!! $Tinvest !!}  </td>
                                @endforeach
                                @php($investAnn = array_sum($investDeLAnnonceurParMedia))
                                <td>
                                    {{$report->numberDisplayer($investAnn)}}
                                </td>
                                <td>
                                    {{ round(100 * $investAnn / $dataTableaux['investGlobalDeLaSelection'], 2)}}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            @foreach ($laListeDesMedias as $m)
                                <?php
                                    $investMediaTotal = array_key_exists($m, $dataTableaux['investParMedia']) ? $report-> numberDisplayer($dataTableaux['investParMedia'][$m]) : 0;
                                ?>
                                <th>
                                    {{$report-> numberDisplayer(array_sum($dataTableaux['partDeVoixParMedia'][$m]))}}
                                </th>
                                <th>
                                    {{$investMediaTotal?? 0}}
                                </th>
                            @endforeach
                            <td>
                                {{$report->numberDisplayer(array_sum($dataTableaux['investParMedia']))}}
                            </td>
                            <td>
                                100
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
</div>
