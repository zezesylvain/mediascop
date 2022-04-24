@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Utilisateurs Connectés",1) !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                   data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" class="table table-responsive table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>NOM et PR&Eacute;NOMS</th>
                                    <th>PROFIL</th>
                                    <th>ADRESSE IP</th>
                                    <th>HEURE DE CONNEXION</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i = 0)
                                @foreach($connected as $k => $r)
                                    @php($i++)
                                    <tr>
                                        <td>{!! $i !!}</td>
                                        <td>{{ $r['name'] }}</td>
                                        <td>{{ $r['profil'] }}</td>
                                        <td>{{$r['ip']}}</td>
                                        <td>{{$r['heureDeConnection']}}</td>
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
@endsection
