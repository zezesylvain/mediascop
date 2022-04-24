@inject('func', 'App\Http\Controllers\core\FunctionController')
@if(count($dataTableData))
    <?php
    $lesEntetes = array_keys($dataTableData[0]);
    $lid = array_search("id", $lesEntetes);
    unset($lesEntetes[$lid]);
    $chemin = route("inlineTexte");
    ?>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>@isset($title) {{$title}} @endisset</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Export Basic</option>
                                        <option value="all">Export All</option>
                                        <option value="selected">Export Selected</option>
                                    </select>
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                       data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                    <tr>
                                        @foreach ($lesEntetes  AS $v)
                                            @if($is_displayable($databaseTable, $v))
                                                @if($v == "est_role")
                                                    <th>{{ $v }}</th>
                                                @else
                                                <th>{{ $v }}</th>
                                                @endif
                                            @endif
                                        @endforeach
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($dataTableData AS $row)
                                        <tr>
                                            @foreach ($lesEntetes  AS $k)
                                                @if($is_displayable($databaseTable, $k))
                                                    <td>
                                                        @if(!$is_inlinable($databaseTable, $k))
                                                            {{ $ec($row[$k], $k) }}
                                                        @else
                                                            @if($k == "est_role")
                                                                <?php
                                                                $checked = $row[$k] == 1 ? "checked='checked'" : "";
                                                                ?>
                                                                <input type="checkbox" class="checkbox-inline" {{$checked}} onclick="sendData('routeID={{$row['id']}}&estRole='+this.checked,'{{route("ajax.estRole")}}','estRoleItem')">
                                                            @else
                                                                <?php
                                                                $itemId = $row["id"];
                                                                $position = "test-$k-$itemId";
                                                                ?>
                                                                <span id="test-{{ $k }}-{{ $row['id'] }}">
                                                        {!! $inlineTexte($itemId, $row[$k] , $k, $databaseTable, "texte", $position) !!}
                                                </span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                @endif
                                            @endforeach
                                            <td>
                                                <a title="Modifier" href="{{route("updateData",[$databaseTable,$row["id"]])}}"><i class="fa fa-pencil"></i></a>
                                                <a title="Supprimer" href="{{route("updateData",[$databaseTable,$row["id"]])}}"><i class="fa fa-trash"></i></a>
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
@endif
