<div class="panel panel-default">
    <div class="panel-heading">
        Les dernières speednews
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <div id=""  class="dataTables_wrapper form-inline" role="grid">
                <table class="table table-striped table-bordered table-hover dataTables-listing dataTable no-footer liste-campagne" id=""  aria-describedby="DataTables_Table_0_info">
                    <thead>
                    <tr role="row">
                        <th class="sorting_desc" tabindex="0"
                            aria-controls="DataTables_Table_0" rowspan="1"
                            colspan="1" style="width: 5px;" aria-sort="descending"
                            aria-label="Date: activate to sort column ascending"
                            width="5%">Date
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="DataTables_Table_0" rowspan="1"
                            colspan="1" style="width: 45px;"
                            aria-label="Titre campagne: activate to sort column ascending"
                            width="45%">Titre campagne
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="DataTables_Table_0" rowspan="1"
                            colspan="1" style="width: 5px;"
                            aria-label="Média: activate to sort column ascending"
                            width="5%">Média
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="DataTables_Table_0" rowspan="1"
                            colspan="1" style="width: 15px;"
                            aria-label="Annonceur (secteur): activate to sort column ascending"
                            width="15%">Annonceur 
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="DataTables_Table_0" rowspan="1"
                            colspan="1" style="width: 15px;"
                            aria-label="Support: activate to sort column ascending"
                            width="15%">Support
                        </th>
                        <th class="sorting" tabindex="0"
                            aria-controls="DataTables_Table_0" rowspan="1"
                            colspan="1" style="width: 15px;"
                            aria-label="Visuel: activate to sort column ascending"
                            width="15%">Visuel
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($lesSpeednews AS $sp)
                            <tr>
                                <td> {{ $sp['date'] }} </td>
                                <td>
                                    <a href="{{ route('reporting.detailCampagne', ['cid' => $sp['campagnetitle_id']]) }}" target="_blank">
                                        {!! $sp['campagne'] !!}
                                    </a>
                                </td>
                                <td>{{ $sp['media'] }}</td>
                                <td>{{ $sp['annonceur'] }}</td>
                                <td> {{ $sp['support'] }} </td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
