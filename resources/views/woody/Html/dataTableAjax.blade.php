@inject('func', 'App\Http\Controllers\core\FunctionController')
@inject('xEdit', 'App\Http\Controllers\core\XeditableController')
@if(count($dataTableData))
<?php
$lesEntetes = array_keys($dataTableData[0]);
$lid = array_search("id", $lesEntetes);
unset($lesEntetes[$lid]);
$chemin = route("inlineTexte");
$routeUpdate = !array_key_exists ('routeUpdate',$options) ? "updateData" : $options['routeUpdate'];

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
                                   data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id">
                                <thead>
                                <tr>
                                    <th data-field="id" data-visible="false">ID</th>
                                    @foreach ($lesEntetes  AS $v)
                                        <?php $dataEditable = false; ?>
                                        @if($is_displayable($databaseTable, $v))
                                            @if($is_inlinable($databaseTable, $v))
                                                @php
                                                    $dataEditable = "true";
                                                    $columnOptions = $xEdit->getColumnType ($databaseTable,$v);
                                                    $type = $columnOptions['type'];
                                                    $source = $xEdit->getSource($v,$type);
                                                    $dataViewFormat = $columnOptions['dateViewFormat'];
                                                    $dateFormat = $columnOptions['dateFormat'];
                                                @endphp
                                            @endif
                                            <th data-field="{{$v}}" data-editable="{{$dataEditable}}" data-editable-type="{{$type ?? "text"}}" data-editable-source="[{{$source ?? ""}}]" data-editable-viewformat="{{$dataViewFormat ?? ""}}" data-editable-format="{{$dateFormat ?? ""}}">{!! $formatTableHeader($v) !!}</th>
                                        @endif
                                    @endforeach
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($dataTableData AS $row)
                                    <tr>
                                        <td>{{$row["id"]}}</td>
                                        @foreach ($lesEntetes  AS $k)
                                            @if($is_displayable($databaseTable, $k))
                                                @if(!$is_inlinable($databaseTable, $k))
                                                    <td>{!! $ec($row[$k], $k) !!}</td>
                                                @else
                                                    <?php
                                                    $itemId = $row["id"];
                                                    $position = "test-$k-$itemId";
                                                    ?>
                                                    <td>{!! $row[$k] !!}</td>
                                                @endif
                                            @endif
                                        @endforeach
                                        <td>
                                            <a title="Modifier" href="{{route($routeUpdate,[$table,$row["id"]])}}" class="btn btn-link"><i class="fa fa-pencil"></i></a>

                                            <a title="Supprimer" href="#" class="btn btn-link" data-toggle="modal" data-target="#PrimaryModalhdbgcl"><i class="fa fa-trash"></i></a>
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

<div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-color-modal bg-color-1">
                <h4 class="modal-title">BG Color Header Modal</h4>
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <div class="modal-body">
               <div id="PrimaryModalhdbgclItem"></div>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" href="#">Cancel</a>
                <a href="#">Process</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $.fn.editable.defaults.url = '{{route ('xEditableUpdate')}}';
        $.fn.editable.defaults.params = function (params) {
            params._token = $("meta[name=_token]").attr("content");
            params.table = "{{$databaseTable}}";
            params.source = [
                {value: 1, text: 'Male'},
                {value: 2, text: 'Female'}
            ]
            return params;

        };

        })
</script>

@endif
