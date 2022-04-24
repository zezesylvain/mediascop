@inject('func', 'App\Http\Controllers\core\FunctionController')
@inject('xEdit', 'App\Http\Controllers\core\XeditableController')
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
                            <div class="">
                                <div class="static-table-list">
                                    <table id="table" class="table sparkle-table">
                                        <thead>
                                        <tr>
                                            @foreach ($lesEntetes  AS $v)
                                                @if($is_displayable($databaseTable, $v))
                                                    <th>{!! $formatTableHeader($v) !!}</th>
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
                                                                <?php
                                                                $itemId = $row["id"];
                                                                $position = "test-$k-$itemId";
                                                                $libelle = $row[$k];
                                                                $pk = $row["id"];
                                                                
                                                                ?>
                                                                {!! $xEdit->xEditableJS ($databaseTable,$libelle,$pk) !!}
                                                            @endif
                                                        </td>
                                                    @endif
                                                @endforeach
                                                <td>
                                                    @if(array_key_exists('update',$options))
                                                        <a title="Modifier" href="{{route($options['update'],[$table,$row["id"]])}}"><i class="fa fa-pencil"></i></a>
                                                    @else
                                                        <a title="Modifier" href="{{route("updateData",[$table,$row["id"]])}}"><i class="fa fa-pencil"></i></a>
                                                    @endif
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
    </div>
    
    @foreach ($lesEntetes  AS $v)
        @if($is_displayable($databaseTable, $v))
            @if($is_inlinable($databaseTable, $v))
                @php
                    $dataEditable = $v;
					$xEdit->xEditableScriptJS ($databaseTable,$v)
                @endphp
            @endif
        @endif
    @endforeach
    
    
    <script type="text/javascript">
        $(document).ready(function () {
            $.fn.editable.defaults.url = "{{route('updatexEditable')}}";
            $("#table a.x-edit").editable({
                validate: function(value) {
                    var current_pk = $ (this) .data ();
                    console.log(current_pk);
                    if($.trim(value) == '') {
                        return 'Value is required.';
                    }
                }
            });
        })
    </script>

@endif
