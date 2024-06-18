@if(count ($tabdata))
    @php($ij = 0)
    <hr class="trait-bleu">
        <div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">
            <h4>CAMPAGNES VALID&Eacute;ES</h4>
            <div class="data-table-area mg-tb-15">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="sparkline30-list">
                                <div class="sparkline30-graph">
                                    <div class="">
                                        <table class="table table-bordered table-responsive table-striped" >
                                            <thead>
                                            <tr>
                                                <th style="width: 10%;">Date</th>
                                                <th style="width: 20%;">Annonceur</th>
                                                <th style="width: 25%;">Op&eacute;ration</th>
                                                <th style="width: 45%;">Campagnes</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($tabdata as $rowvalue)
                                            <tr>
                                                <td>
                                                    {{ $date2Fr($rowvalue['adddate'],'d/m/Y H:i:s') }}
                                                </td>
                                                <td>
                                                    {!! $rowvalue['raisonsociale'] !!}
                                                </td>
                                                <td>
                                                    {!! $rowvalue['operationname'] !!}
                                                </td>
                                                <td>
                                                    {!! $rowvalue['title'] !!}
                                                </td>
                                                <td>
                                                    @if($valide == 1)
                                                        <a href="{{route ('saisie.creerCampagne',[$rowvalue['id']])}}" title="Modifier la campagne!"><i class="fa fa-pencil"></i> </a>
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
@endif
