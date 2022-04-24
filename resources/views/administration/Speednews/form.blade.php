@extends('layouts.admin')
@section('container')
    {!! $titreHtml("ChoisiUtilisateurs et Speed News") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline30-list">
                        <div class="sparkline30-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                       data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id" >
                                    <thead>
                                    <tr>
                                        <th style="width: 10%;">N°</th>
                                        <th style="width: 50%;">NOM & PRENOMS</th>
                                        <th style="width: 30%;">EMAIL</th>
                                        <th style="width: 10%;">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i = 0)
                                    @foreach ($users as $rowvalue)
                                        <?php
                                        $i++;
                                        $checked = in_array($rowvalue['id'],$userSpeednews) ? "checked='checked'" : "";
                                        ?>
                                        <tr>
                                            <td style="vertical-align: middle!important;text-align: center!important;">{!! $i !!}</td>
                                            <td>{!! $rowvalue['name'] !!}</td>
                                            <td>{!! $rowvalue['email'] !!}</td>
                                            <td style="vertical-align: middle;">
                                                <input type="checkbox" name="speednews" value="{{$rowvalue['id']}}" onclick="addUserSpeedNews(this.value,this.checked)" {{ $checked }}>
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

    <script type="application/javascript">
        function addUserSpeedNews(userID,etat){
            $.ajax({
                url: '{{ route('ajax.addUserSpeedNews') }}',
                data: {
                    userID : userID,
                    etat : etat,
                },
                dataType: 'JSON',
                type: 'POST',
                success: function (result) {
                    //console.log(result);
                }
            })
        }
    </script>
@endsection
