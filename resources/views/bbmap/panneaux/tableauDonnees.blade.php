@php($TableauDesMap = $listeDesMap['TableauDesMap'])
<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-listing dataTable no-footer liste-campagne"
                   id="xxxdataTables-listing"
                   aria-describedby="xxxdataTables-listing_info">
                <thead>
                <tr role="row">
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Localité: activate to sort column ascending">
                        Localité
                    </th>

                    <th class="sorting_desc" tabindex="0"  aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;" aria-sort="descending" aria-label="Date: activate to sort column ascending">
                        Date
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Titre de Campagne: activate to sort column ascending">
                        Titre de Campagne
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Annonceur: activate to sort column ascending">
                        Annonceur
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Investissement: activate to sort column ascending">
                        Investissement
                    </th>
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Nombre: activate to sort column ascending">
                        Nombre
                    </th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($TableauDesMap as $localID => $rrs)
                    <?php
                        $cpt = 0;
                        $rsp = count($rrs);
                        $localite = \App\Models\Localite::find($localID)->name;
                    ?>
                    @foreach($rrs as $rr)
                        <tr>
                            @if($cpt === 0)
                                <th style="vertical-align: middle;text-align: left;" rowspan="{{ $rsp }}">
                                    {!! $localite !!}
                                </th>
                            @endif
                            <td>{{$rr['Date']}}</td>
                            <td>{!! $rr['titre'] !!}</td>
                            <td>{{$rr['annonceur']}}</td>
                            <td>{{$rr['investissement']}}</td>
                            <td>{{$rr['nombre']}}</td>
                        </tr>
                        @php($cpt++)
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
