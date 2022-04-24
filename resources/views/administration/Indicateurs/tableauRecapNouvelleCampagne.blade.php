<div class="sparkline13-graph">
    <div class="datatable-dashv1-list custom-datatable-overright">
        <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
               data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar" data-id-field="id">
            <thead>
            <tr>
                <th>DATE</th>
                <th>Prénom Nom</th>
                <th>MEDIA</th>
                <th>SECTEUR</th>
                <th>ANNONCEUR</th>
                <th>TITRE DE CAMPAGNE</th>
                <th>ETAT</th>
                <th>VISUALISER</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($lesCampagnesDuJour AS $row)
                @php
                    $tUser = $getTableName ($dbTable ('DBTBL_USERS'))
                @endphp
                <tr>
                    <td>{{$date2Fr ($row["created_at"])}}</td>
                    <td>{{$getChampTable ($getTableName ($dbTable ('DBTBL_USERS')),$row["user"])}}</td>
                    <td>{{$getChampTable ($getTableName ($dbTable ('DBTBL_MEDIAS','db')),$row["media"])}}</td>
                    <td>{{$getChampTable ($getTableName ($dbTable ('DBTBL_USERS')),$row["user"])}}</td>
                    <td>{{$row["id"]}}</td>
                    <td>{{$row["id"]}}</td>
                    <td>{{$row["id"]}}</td>
                    <td>{{$row["id"]}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
