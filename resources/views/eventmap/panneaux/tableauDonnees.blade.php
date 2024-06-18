
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
                    <th class="sorting" tabindex="0"
                        aria-controls="xxxdataTables-listing" rowspan="1"
                        colspan="1" style="width: 0;"
                        aria-label="Lieu: activate to sort column ascending">
                        Lieu
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
                        $compteurLocalite = 0;
                        $rowspan = 0;
                        foreach($rrs  AS  $v):
                            $rowspan += count($v);
                        endforeach;
                    ?>
                    @foreach($rrs as $tab2)
                        <?php
                            $compteurLieu = 0;
                            $n2 = count($tab2);
                        ?>
                        @foreach($tab2 as $rr)
                            <tr>
                                @if($compteurLocalite == 0)
                                    <th style="vertical-align: middle;text-align: left;" rowspan="{{ $rowspan }}">
                                        {{ $rr['localite'] }}
                                    </th>
                                @endif
                                @if($compteurLieu == 0)
                                    <td style="vertical-align: middle;text-align: left;" rowspan="{{ $n2 }}">
                                        {{ $rr['Lieu'] }}
                                    </td>
                                @endif
                                <td>{{ $rr['Date'] }}</td>
                                <td>{!! $rr['titre'] !!}</td>
                                <td>{{ $rr['annonceur'] }}</td>
                                <td>{{ $rr['investissement'] }}</td>
                                <td>{{ $rr['nombre'] }}</td>
                            </tr>
                            @php($compteurLieu++)
                            @php($compteurLocalite++)
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
