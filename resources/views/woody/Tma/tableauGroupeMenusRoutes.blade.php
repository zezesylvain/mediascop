@inject('func', 'App\Http\Controllers\core\FunctionController')
@if(count($dataTableData))
    <?php
    $lesEntetes = array_keys($dataTableData[0]);
    $lid = array_search("id", $lesEntetes);
    unset($lesEntetes[$lid]);
    $chemin = route("inlineTexte");

    ?>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-table"></i> {{$title or ""}} </div>
            <div id="estRoleItem"></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable"  id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        @foreach ($lesEntetes  AS $v)
                            @if($is_displayable($databaseTable, $v))
                                <th>{{ $v }}</th>
                            @endif
                        @endforeach
                        {{--@if(isset($action))--}}
                        <td></td>
                        {{--@endif--}}
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        @foreach ($lesEntetes  AS $v)
                            @if($is_displayable($databaseTable, $v))
                                <th>{{ $v }}</th>
                            @endif
                        @endforeach
                        {{--@if(isset($action))--}}
                        <td></td>
                        {{--@endif--}}
                    </tr>
                    </tfoot>
                    <tbody>

                    @foreach ($dataTableData AS $row)
                       @php($v = \App\Http\Controllers\core\TmaController::routeDeMenu($row['id']))
                       @if(!$v)
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
                                            ?>
                                            <span id="test-{{ $k }}-{{ $row['id'] }}">
                                                    {!! $inlineTexte($itemId, $row[$k] , $k, $databaseTable, "texte", $position) !!}
                                            </span>
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                            {{--@if(isset($action))--}}
                            <td>
                                <input type="checkbox" class="checkbox-inline"  onclick="sendData('routeID={{$row['id']}}&groupemenu='+$('#groupemenus').val()+'&checked='+this.checked,'{{route("ajax.ajouterRoutesDsGroupeMenus")}}','estRoleItem')">
                            </td>
                            {{--@endif--}}
                        </tr>
                       @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer small text-muted"></div>
    </div>
    <!-- /Example DataTables Card-->
@endif
