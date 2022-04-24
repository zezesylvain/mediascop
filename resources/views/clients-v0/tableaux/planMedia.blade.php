@php($dataForPM = $lesDonnees['planMedia'])

                    <table class="mb t-1 table table-striped table-bordered table-hover marcheBancaireTbl">
                        <thead>
                            <tr>
                                <th rowspan="2" class="w40"><b>Annonceurs</b></th>
                                <th rowspan="2" class="w30"><b>Media</b>  </th>
                                <th rowspan="2" class="w15"><b>Insertion</b></th>
                                <th rowspan="2" class="w40"><b>Budget</b></th>
                                <th class="w20"><b>%</b></th>
                            </tr>
                            <tr>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($dataForPM as $Ann => $tabMedia)
                            @php($nb  = count($tabMedia))
                            @php($in = 0)
                            @php($insertion = 0)
                            @php($h = $nb * 15)
                            @php($invest = 0)
                            <tr>
                                <td class="tab-col-left" rowspan="{{$nb}}">{{$Ann}}</td>
                                @foreach ($tabMedia as $m => $t)
                                    @if($in)
                                        <tr>
                                    @endif
                                        <td>{!! $m !!}</td>
                                        <td>{!! $t['ins'] !!}  </td>
                                        <td>{!! $t['invest'] !!}  </td>
                                        <td>  </td>
                                    </tr>
                                    @php($insertion += $t['ins'])
                                    @php($invest = $t['invest'])
                                    @php($in++)
                                @endforeach
                            @if($nb > 1)
                                <tr>
                                    <td colspan="2"><b>Total {{$Ann}}</b></td>
                                    <td>{{$insertion}}</td>
                                    <td>{{$invest}}</td>
                                    <td></td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>