@if(count($dataTableData))
<?php 
    $lesEntetes = $lesEntetes ?? array_keys($dataTableData[0]) ;
    $baseDeDonnees = "zd_menus";
    $chemin = "/";
    $chemin2 = "";
    //dd($lesEntetes);
 ?>
<!-- Example DataTables Card-->
<div class="card mb-3">
          <div class="card-header">
                    <i class="fa fa-table"></i> Data Table Example</div>
          <div class="card-body">
                    <div class="table-responsive">
                              <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                                        <thead>
                                                  <tr>
                                                            @foreach ($lesEntetes  AS $v)
                                                                    <th>{{ $v }}</th>
                                                           @endforeach
                                                  </tr>
                                        </thead>
                                        <tfoot>
                                                  <tr>
                                                           @foreach ($lesEntetes  AS $v)
                                                                    <th>{{ $v }}</th>
                                                           @endforeach
                                                    </tr>
                                        </tfoot>
                                        <tbody>
                                                                      
                                                   @foreach ($dataTableData AS $row)
                                                            <tr>
                                                            @foreach ($lesEntetes  AS $k)
                                                                      <td> 
                                                                                
                                                                                <?php 
                                                                                    $position = "test-$k" ;
                                                                                    
                                                                                ?>
                                                                                          <span id="test-{{ $k }}">
                                                                                                    {!! \App\Http\Controllers\core\FunctionController::aForInputInline(1, $row[$k] , $k, $baseDeDonnees, $chemin, $position, $chemin2) !!}
                                                                                          </span>
                                                                      </td>
                                                            @endforeach
                                                            </tr>
                                                            
                                                  @endforeach
                                        </tbody>
                              </table>
                    </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
</div>
<!-- /Example DataTables Card-->
@endif 
