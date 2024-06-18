<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4>Pubs {{strtolower ($action)}} saisie</h4>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 list-pub-saisie">
                <div class="sparkline30-list">
                    <div class="sparkline30-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <table class="table table-bordered  table-striped"  >
                                <thead>
                                <tr>
                                    <th width=""></th>
                                    <th width="">Date</th>
                                    {!! $thDuree !!}
                                    {!! $thSupport !!}
                                    <th>Titre de campagne</th>
                                    <th width="">Tarif</th>
                                    <th width="">Coeff</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i = 0)
                                @foreach($pubs as $r)
                                    @php($i++)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$date2Fr ($r['date'])}}</td>
                                        @if($action === "TELEVISION" || $action === "RADIO")
                                            <td>{{$r['heure']}}</td>
                                        @endif
                                        @if($action !== "AFFICHAGE")
                                            <td>{{$getChampTable ($dbTable ('DBTBL_SUPPORTS','db'),$r['support'])}}</td>
                                        @endif
                                        @php($campagneTitle = $getChampTable ($dbTable ('DBTBL_CAMPAGNES','db'),$r['campagne'],'campagnetitle'))
                                        <td>{!! $getChampTable ($dbTable ('DBTBL_CAMPAGNETITLES','db'),$campagneTitle,'title') !!}</td>
                                        <td>{{$r['tarif']}}</td>
                                        <td>{{$r['coeff']}}</td>
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
