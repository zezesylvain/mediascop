<hr class="trait-bleu">
@if($valide === 0)
    <form method="post" action="{{route ("dashbord.validerPubs")}}">
    {{csrf_field()}}
@endif
        <div class="tab-content-details shadow-reset" style="padding: 10px 0 10px 0 ;">
            <h4></h4>
            <div class="data-table-area mg-tb-15">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="sparkline30-list">
                                <div class="sparkline13-hd">
                                    <div class="main-sparkline13-hd">
                                        @if($valide == 0)
                                            <h1>Pubs non validées</h1>
                                        @else
                                            <h1>Pubs validées</h1>
                                        @endif
                                    </div>
                                </div>
                                <div class="sparkline30-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
                                        <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                               data-show-columns="true" data-show-pagination-switch="true"
                                               data-show-refresh="true" data-key-events="true" data-show-toggle="true"
                                               data-resizable="true" data-cookie="true"
                                               data-cookie-id-table="saveId" data-show-export="false"
                                               data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id">
                                            @if($valide === 0)
                                                <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false">ID</th>
                                                    <th data-field="date" data-editable="true"
                                                        data-editable-type="date"
                                                        data-editable-viewformat="dd/mm/yyyy"
                                                        data-editable-format="yyyy-mm-dd">Date
                                                    </th>
                                                    {!! $thDuree !!}
                                                    {!! $thOperation !!}
                                                    {!! $thHeure !!}
                                                    {!! $thEmplacement !!}
                                                    {!! $thAffichage !!}
                                                    @if($action !== 'AFFICHAGE')
                                                        <th data-field="support" data-editable="true"
                                                            data-editable-type="select"
                                                            data-editable-source="[{{$source}}]">Support
                                                        </th>
                                                    @endif
                                                    <th>Titre de campagne</th>
                                                    @if($action !== 'HORS-MEDIA')
                                                        <th data-field="tarif" data-editable="true"
                                                            data-editable-type="text">Tarif
                                                        </th>
                                                        <th data-field="coeff" data-editable="true"
                                                            data-editable-type="text">Coeff
                                                        </th>
                                                    @endif
                                                    {{-- {!! $thHorsMedia !!}--}}
                                                    <th>Agent de saisie</th>
                                                    <th>Erreur</th>
                                                    <th>
                                                        <input type="checkbox"
                                                               onChange="checkboxChecker('listpub', this.checked, '{{count ($listpub)}}');">
                                                    </th>
                                                </tr>
                                                </thead>
                                            @endif
                                            @if($valide == 1)
                                                <thead>
                                                <tr>
                                                    <th data-visible="false">ID</th>
                                                    <th>Date</th>
                                                    {!! $thDuree !!}
                                                    {!! $thOperation !!}
                                                    {!! $thHeure !!}
                                                    {!! $thEmplacement !!}
                                                    {!! $thAffichage !!}
                                                    @if($action !== 'AFFICHAGE')
                                                        <th>Support</th>
                                                    @endif
                                                    <th>Titre de campagne</th>
                                                    {!! $thHorsMedia !!}
                                                    @if($action != 'HORS-MEDIA')
                                                        <th>Tarif</th>
                                                        <th>Coeff</th>
                                                    @endif
                                                    @if($action === 'AFFICHAGE')
                                                        <th></th>
                                                    @endif
                                                    {{--<th>Agent de saisie</th>--}}
                                                </tr>
                                                </thead>
                                            @endif
                                            <tbody>
                                            {!! $tBody !!}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($valide === 0)
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <hr>
                                {!! \App\Http\Controllers\core\FormController::champSubmit ("Valider") !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

@if($valide === 1)
    </form>
@endif
<script type="text/javascript">
    $(document).ready(function () {
        $.fn.editable.defaults.url = '{{route ('xEditableUpdate')}}';
        $.fn.editable.defaults.params = function (params) {
            params._token = $("meta[name=_token]").attr("content");
            params.table = "{{$databaseTable}}";
            params.source = [
                {value: 1, text: 'Male'},
                {value: 2, text: 'Female'}
            ]
            return params;
        };

    })
</script>

