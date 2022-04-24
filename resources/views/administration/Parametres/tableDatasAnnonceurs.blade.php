<div class="data-table-area mg-tb-15">
    @inject('func', 'App\Http\Controllers\core\FunctionController')
    @inject('xEdit', 'App\Http\Controllers\core\XeditableController')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <hr class="trait-bleu">
                <div class="sparkline13-list">
                    <div class="sparkline13-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright static-table-list">
                            <table id="table" class="table dataTables-listing table-bordered" data-toggle="" data-pagination="true" data-search="true" style="vertical-align: middle!important;">
                                <thead>
                                <tr>
                                    <th data-field="id" data-visible="false"></th>
                                    @foreach ($dataTableHeader  AS $v => $d)
                                            <th>{!! $formatTableHeader($d) !!}</th>
                                    @endforeach
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i = 0)
                                @foreach ($annonceurs AS $row)
                                    @php($i++)
                                    <tr>
                                        <td>{{$i}}</td>
                                        @foreach ($dataTableHeader  AS $k => $r)
                                            @if($k == "couleur")
                                                <td class="">
                                                    <input class="jscolor form-control" name="coul" value="{{$row[$k]}}" onchange="sendData('id={{$row["id"]}}&coul='+this.value, '{{route ('parametre.updateColor')}}', 'couleur-{{$row["id"]}}');">
                                                    <div id="couleur-{{$row["id"]}}"></div>
                                                </td>
                                            @elseif($k == "logo")
                                                <td>
                                                    {!! \App\Http\Controllers\Administration\ParametreController::getLogoAnnonceur ($row["id"]) !!}
                                                </td>
                                            @else
                                                <?php
                                                $itemId = $row["id"];
                                                $position = "test-$k-$itemId";
                                                ?>
                                                <td id="test-{{ $k }}-{{ $row['id'] }}">
                                                    <span id="test-{{ $k }}-{{ $row['id'] }}">
                                                    {!! $inlineTexte($itemId, $row[$k] , $k, $databaseTable, "texte", $position) !!}
                                                    </span>
                                                </td>
                                            @endif
                                        @endforeach
                                        <td>
                                            <a data-toggle="tooltip" title="Modifier l'annonceur" class="pd-setting-ed" href="{{route ('parametre.updateAnnonceur',[$row["id"]])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                            <a data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
