@inject('func', 'App\Http\Controllers\core\FunctionController')
@if(count($dataTableData))
<?php
$lesEntetes = array_keys($dataTableData[0]);
$lid = array_search("id", $lesEntetes);
unset($lesEntetes[$lid]);
$chemin = route("inlineTexte");
?>
<!-- Example DataTables Card-->


<div class="table-responsive">
          <table class="table table-bordered dataTable1"  id="" width="100%" cellspacing="0">
                    <thead>
                              <tr>
                                        @foreach ($lesEntetes  AS $v)
                                        @if($is_displayable($databaseTable, $v))
                                        <th>{{ $v }}</th>
                                        @endif
                                        @endforeach
                              </tr>
                    </thead>
                    <tfoot>
                              <tr>
                                        @foreach ($lesEntetes  AS $v)
                                        @if($is_displayable($databaseTable, $v))
                                        <th>{{ $v }}</th>
                                        @endif
                                        @endforeach
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
                              </tr>
                              @endforeach
                    </tbody>
          </table>
</div>

<!-- /Example DataTables Card-->
@endif
