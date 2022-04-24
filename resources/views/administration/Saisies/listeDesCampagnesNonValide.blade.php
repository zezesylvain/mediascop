@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Modifier Campagnes") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="data-table-area mg-tb-15">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table class="table table-bordered  table-striped" id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                       data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" >
                                    <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false">ID</th>
                                        <th width=""></th>
                                        <th width="">Date</th>
                                        <th width="">Opération</th>
                                        <th data-field="title" data-editable="true" data-editable-type="text">Titre de campagne</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i = 0)
                                    @foreach($campagneSaisieNonValider as $r)
                                        @php($i++)
                                        <tr>
                                            <td>{{$r['id']}}</td>
                                            <td>{{$i}}</td>
                                            <td>{{$date2Fr ($r['adddate'])}}</td>
                                            <td>{{$getChampTable ($dbTable ('DBTBL_OPERATIONS','db'),$r['operation'])}}</td>
                                            <td>{!! $r['title'] !!}</td>
                                            <td>
                                        
                                                <a title="Supprimer" href="#" class="btn btn-link" data-toggle="modal" data-target="#PrimaryModalhdbgcl" onclick="sendData('table={{$dbTable ('DBTBL_CAMPAGNETITLE_NON_VALIDES','db')}}&pk={{$r['id']}}','{{route ('ajax.deleteModalDatas')}}','PrimaryModalhdbgclItem')"><i class="fa fa-trash"></i></a>
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

    <script type="text/javascript">
        $(document).ready(function () {
            $.fn.editable.defaults.url = '{{route ('xEditableUpdate')}}';
            $.fn.editable.defaults.params = function (params) {
                params._token = $("meta[name=_token]").attr("content");
                params.table = "{{$dbTable ('DBTBL_CAMPAGNETITLE_NON_VALIDES','db')}}";
                params.source = [
                    {value: 1, text: 'Male'},
                    {value: 2, text: 'Female'}
                ]
                return params;
            
            };
        
        })
    </script>

    <div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"></h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="PrimaryModalhdbgclItem"></div>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" href="#">Cancel</a>
                </div>
            </div>
        </div>
    </div>

@endsection
