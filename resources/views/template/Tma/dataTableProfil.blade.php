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
                            {{--@if(isset($action))--}}
                            <td>
                                <a title="Modifier" href="{{route("updateData",[$databaseTable,$row["id"]])}}"><i class="fa fa-pencil"></i></a>
                                <a title="Ajouter Role" href="{{route("makeProfilRole",[$row["id"]])}}"><i class="fa fa-user"></i></a>
                            </td>
                            {{--@endif--}}
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer small text-muted"></div>
    </div>
    <!-- /Example DataTables Card-->
@endif
