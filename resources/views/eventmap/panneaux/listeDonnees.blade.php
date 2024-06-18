<div class="">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-responsive table-striped dataTables-listing" id="table">
                <thead>
                <tr>
                    <th style="width: 20%;"></th>
                    <th style="width: 65%;"></th>
                    <th style="width: 15%;"></th>
                </tr>
                </thead>
                 <tbody>
                 @foreach($listeDesMap as $r)
                     <tr>
                         <td style="width: 20%;vertical-align: middle;">
                             <div class="col-sm-12 image-pane">
                                 <img src="{{$r['infowindows']['docampagne'] ?? ''}}" alt="" class="img-thumbnail">
                             </div>
                         </td>
                         <td style="width: 65%;">
                             <div class="col-sm-12 info-pane">
                                 <h5 class="titre-pane">{{$r['infowindows']['titre']}}</h5>
                                 <p>
                                     <span>Secteur: </span><b>{{$r['infowindows']['secteur']}}</b>;
                                     <span>Annonceur: </span><b>{{$r['infowindows']['annonceur']}}</b>;
                                     <span>Campagne: </span><b>{{$r['infowindows']['titre']}}</b>;
                                     <span>Nature: </span><b>{{$r['infowindows']['nature'] ?? ''}}</b>;
                                     <span>Format: </span><b>{{$r['infowindows']['format'] ?? ''}}</b>;
                                     <span>Localité: </span><b>{{$r['infowindows']['localite'] ?? ''}}</b>
                                 </p>
                             </div>
                         </td>
                         <td style="width: 15%;vertical-align: middle;">
                             <a href="" class="btn btn-primary btn-block" style="">Afficher details</a>
                         </td>
                     </tr>
                 @endforeach
                 </tbody>
            </table>
        </div>
    </div>
</div>
