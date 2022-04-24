@if(count($dataTableData))
    @inject('func', 'App\Http\Controllers\core\FunctionController')
    @inject('xEdit', 'App\Http\Controllers\core\XeditableController')
    
    <?php
    $lesEntetes = array_keys($dataTableData[0]);
    $lid = array_search("id", $lesEntetes);
    unset($lesEntetes[$lid]);
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
                                       data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id">
                                    <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false">ID</th>
                                        @foreach ($lesEntetes  AS $v)
                                           
                                                @php
                                                    $dataEditable = "true";
                                                    $columnOptions = $xEdit->getColumnType ($table,$v);
                                                    $type = $columnOptions['type'];
                                                    $dataViewFormat = $columnOptions['dateViewFormat'];
                                                    $dateFormat = $columnOptions['dateFormat'];
                                                    $source = $xEdit->getSource($v,$type);
                                                @endphp
                                           
                                                <th data-field="{{$v}}" data-editable="{{$dataEditable}}" data-editable-type="{{$type ?? "text"}}" data-editable-source="[{{$source ?? ""}}]" data-editable-viewformat="{{$dataViewFormat ?? ""}}" data-editable-format="{{$dateFormat ?? ""}}">{!! $formatTableHeader($v) !!}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($dataTableData AS $row)
                                        <tr>
                                            <td>{{$row["id"]}}</td>
                                            @foreach ($lesEntetes  AS $k)
                                                    <td>{!! $row[$k] !!}</td>
                                            @endforeach
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
