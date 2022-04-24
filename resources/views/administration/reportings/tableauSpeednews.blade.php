<div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline30-list">
                        <div class="sparkline30-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%;">Date</th>
                                        <th style="width: 35%;">Titre Campagne</th>
                                        <th style="width: 10%;">Media</th>
                                        <th style="width: 25%;">Annonceur</th>
                                        <th style="width: 10%;">Support</th>
                                        <th style="width: 15%;">Visuel</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tableauSpeednews as $rowvalue)
                                        <tr>
                                            <td>
                                                {{$date2Fr ($rowvalue['date'])}}
                                            </td>
                                            <td>
                                                {{$rowvalue['title']}}
                                            </td>
                                            <td>
                                                {{$rowvalue['media']}}
                                            </td>
                                            <td>
                                                {{$rowvalue['annonceur'].'('.$rowvalue['secteur'].')'}}
                                            </td>
                                            <td>
                                                {{$rowvalue['support']}}
                                            </td>
                                            <td>
                                                {!! $rowvalue['visuel'] !!}
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

