<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-listing dataTable no-footer liste-campagne"
                   id="xxxdataTables-listing"
                   aria-describedby="xxxdataTables-listing_info">
                <thead>
                <tr role="row">
                    <th class="sorting_desc" tabindex="0"  aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;" aria-sort="descending" aria-label="Période: activate to sort column ascending">
                        Période
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Périodicité: activate to sort column ascending">
                        Périodicité
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Libellé: activate to sort column ascending">
                        Libellé
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Auteur: activate to sort column ascending">
                        Auteurs
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label=": activate to sort column ascending"><i
                            class="fa fa-download"></i></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lesrapports as $lesrapport)
                    @php
                        $extfile = explode('.', $lesrapport['file']) ;
						$ex = $extfile[count($extfile)-1] ;
						$icon = \App\Http\Controllers\Client\ReportingController::icone($ex);
						$urldounload = route("reporting.getFile", [encrypt($lesrapport['file'])]);
						//$urldounload = "#";
                    @endphp
                    <tr>
                        <td>{{$lesrapport['periode']}}</td>
                        <td>{{$lesrapport['periodicitename']}}</td>
                        <td>{{$lesrapport['title']}}</td>
                        <td>{{$lesrapport['auteurs']}}</td>
                        <td class="center">
                            <a target="_blank" href="{{$urldounload}}" title="Télécharger {{$lesrapport['title']}}">
                                <i class="fa fa-file{{$icon}}-o"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
