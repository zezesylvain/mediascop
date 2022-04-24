@inject('func', 'App\Http\Controllers\core\FunctionController')
@if(count($dataTableData))
<?php
$lesEntetes = array_keys($dataTableData[0]);
$lid = array_search("id", $lesEntetes);
unset($lesEntetes[$lid]);
$chemin = route("inlineTexte");
$searchLink = route("SearchEleve");
?>
<!-- Example DataTables Card-->
<div class="card mb-3">
      <div class="card-header">
            <div class="row">
                  <div class="col-xs-6 col-sm-4">
                            <h4> <i class="fa fa-users"></i> {{$titre}}</h4>
                  </div>
                  <div class="col-xs-6 col-sm-8 pull-right">
                      <a type="button" href="{{route('imprimerListeClasse',[$classe["id"],'notes'])}}" target="_blank" class="btn btn-danger"><i class="fa fa-print"></i> Imprimer liste de report des notes </a>
                      <a type="button" href="{{route('imprimerListeClasse',[$classe["id"],'administration'])}}" target="_blank" class="btn btn-dark "><i class="fa fa-print"></i> Imprimer la liste de la classe</a>
                  </div>
            </div>
      </div>
      <div class="card-body" id="{{$searchId}}">
            <div class="table-responsive">
                <table class="table table-bordered dataTable"  id="" width="100%" cellspacing="0">
                    <thead>
                          <tr>
                                @foreach ($lesEntetes  AS $v)
                                    @if($is_displayable($databaseTable, $v))
                                    <th>{{ $formatTableHeader($v) }}</th>
                                    @endif
                                @endforeach
                              <th></th>
                          </tr>
                    </thead>
                    <tfoot>
                          <tr>
                                @foreach ($lesEntetes  AS $v)
                                    @if($is_displayable($databaseTable, $v))
                                    <th>{{ $formatTableHeader($v) }}</th>
                                    @endif
                                @endforeach
                              <th></th>
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
                                <td>
                                    <a title="Modifier" target="_blank" href="{{route("updateDataEleve",[$row['classe'],$row["id"]])}}"><i class="fa fa-pencil"></i></a>
                                    @php(\App\Http\Controllers\XeryaAdmin\GestionInscriptionController::trouverLV2($row["id"],$row["classe"]))

                                    {!! \App\Http\Controllers\XeryaAdmin\EffectifsController::eleveSupprimable($row['id'],$row['classe']) !!}
                                </td>
                          </tr>
                          @endforeach
                    </tbody>
                </table>
            </div>
      </div>
</div>
<!-- /Example DataTables Card-->
@endif

<script>
    $(document).ready(function() {
        $('.dataTable').DataTable();
    });
</script>
