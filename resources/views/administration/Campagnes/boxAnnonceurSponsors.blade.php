<div class="dual-list-box-inner" id="sponsorsItem">
    <div class="row">
        <div class="col-lg-4 col-md-4">
        
        </div>
        <div class="col-lg-8 col-md-8 form-group-inner">
            <div class="input-group custom-go-button">
                <span class="input-group-btn"><button class="btn btn-success" onclick="sendData('filter='+$('#input-filter-ann').val()+'&campagnetitle={{$idcamptitle}}','{{route ("ajax.filterAnnonceurs")}}','listAnnonceurItem')">Filtrer</button></span>
                <input type="text" class="form-control" placeholder="" id="input-filter-ann">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4">
            @include("administration.Campagnes.listeDesSponsors")
        </div>
        <div class="col-lg-8 col-md-8">
            @include("administration.Campagnes.listeDesAnnonceurs")
        </div>
    </div>
</div>
