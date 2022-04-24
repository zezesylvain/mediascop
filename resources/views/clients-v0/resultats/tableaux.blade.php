
    @inject("reporting", "\App\Http\Controllers\Client\ReportingController")
     @foreach($parAnnonceur AS $item => $tab)
        @php($les2ndKeys = $reporting::get2ndKeyInTab($reporting::transformTripleDataForBarChart($tab)))



        <div class="col-xs-12 bordered">
            <div class="row">
                <div class="col-xs-12 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Insertion et Investissement par Annonceur et par  {{$item}}
                        </h3>
                        <section id="unseen{{str_replace(' ', '', $item)}}">
                            <div class="table-responsive">
                                <table class="table table-bordered table-too-long table-responsive">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        @foreach ($les2ndKeys as $k2)
                                            <th colspan="2"> {!! $k2 !!} </th>
                                            @php($investDeItem1[$k2] = [] )
                                        @endforeach
                                        <th>TOTAL</th>
                                        <th>%</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        @foreach ($les2ndKeys as $k2)
                                            <th> ins </th>
                                            <th> invest </th>
                                            @php($investDeItem1[$k2] = ['invest' => 0, 'insertion' => 0])
                                        @endforeach
                                        <th> Invest </th>
                                        <th> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $investDeItem1 = [];
                                    ?>
                                    @foreach ($tab as $k1 => $r)
                                        @php($bg = "")
                                        @if(array_key_exists($k1, $listDesCouleursDesAnnonceur))
                                            @php($annColor = $listDesCouleursDesAnnonceur[$k1])
                                            @php($bg = "background-color:#$annColor;")
                                        @endif
                                        <tr>
                                            <td class="tab-col-left" style="{{$bg}}">{!! $k1 !!}</td>
                                            <?php
                                            $nbIns = 0;
                                            $nbInv = 0;
                                            ?>
                                            
                                            @foreach ($les2ndKeys as $k2)
                                                @php($invP = $r[$k2]['invest'] ?? 0)
                                                @php($invK = number_format($invP, 0, ',', ' ') )
                                                @php($inVIn = $invK == 0 ? "" : $invK)
                                                <td> {{ $r[$k2]['insertion'] ?? ''}} </td>
                                                <td> {{ $inVIn }} </td>
                                                @php($nbInv += $r[$k2]['invest'] ?? 0 )
                                                @php($nbIns += $r[$k2]['insertion'] ?? 0 )
                                                @if(!array_key_exists($k2, $investDeItem1))
                                                    @php($investDeItem1[$k2]['invest'] = $r[$k2]['invest'] ?? 0)
                                                    @php($investDeItem1[$k2]['insertion'] = $r[$k2]['insertion'] ?? 0)
                                                @else
                                                    @php($investDeItem1[$k2]['invest'] += $r[$k2]['invest'] ?? 0)
                                                    @php($investDeItem1[$k2]['insertion'] += $r[$k2]['insertion'] ?? 0)
                                                @endif
                                            @endforeach
                                                <td><b>{{number_format($nbInv, 0, ',', ' ')}}</b></td>
                                                <td></td>
                                        </tr>
                                    @endforeach
                                    <tfoot>
                                        <tr class="stotal">
                                            <th>Total</th>
                                                @php($nbInv = 0 )
                                                @php($nbIns = 0 )
                                            @foreach ($les2ndKeys as $k2)
                                                @php($nbInsert = $investDeItem1[$k2]['insertion'] ?  number_format($investDeItem1[$k2]['insertion'], 0, ',', ' ') : "")
                                                @php($nbInvest = $investDeItem1[$k2]['invest'] ?  number_format($investDeItem1[$k2]['invest'], 0, ',', ' ') : "")
                                                <th> {{ $nbInsert }} </th>
                                                <th> {{ $nbInvest }} </th>
                                                @php($nbInv += $investDeItem1[$k2]['invest'] )
                                                @php($nbIns += $investDeItem1[$k2]['insertion'] )
                                            @endforeach
                                            @php($nbInvest = $nbInv ? number_format($nbInv, 0, ',', ' ') : "")
                                                <td><b>{{$nbInvest}}</b></td>
                                                <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>    
    @endforeach
 