<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3>Votre sélection</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <h4>
                                    P&eacute;riode: Du {{ $date_debut }} au {{ $date_fin }}   
                                </h4> 
                                @if($lesPSecteurs)
                                <h4>
                                    Secteurs:  
                                    
                                </h4> 
                                <p>
                                    @foreach($lesPSecteurs AS $se)
                                            {{ $se['name'] ?? "" }} |
                                    @endforeach
                                </p>
                                @endif
                                
                                @if($lesPAnnonceurs)
                                <h4>
                                    Annonceurs:  
                                    
                                </h4> 
                                <p>
                                    @foreach($lesPAnnonceurs AS $se)
                                            {{ $se['name'] ?? "" }} |
                                    @endforeach
                                </p>
                                @endif
                                @if($lesPMedias)
                                <h4>
                                    M&eacute;dias:  
                                    
                                </h4> 
                                <p>
                                    @foreach($lesPMedias AS $se)
                                            {{ $se['name'] ?? "" }} |
                                    @endforeach
                                </p>
                                @endif
                                
                                @if($lesPSupports)
                                <h4>
                                    Supports:  
                                    
                                </h4> 
                                <p>
                                    @foreach($lesPSupports AS $se)
                                            {{ $se['name'] ?? "" }} |
                                    @endforeach
                                </p>
                                @endif
                                
                             </div>
                        </div>
                    </div>
                </div> <!-- END row -->
            </div> <!-- END panel-body -->
        </div> <!-- END panel panel-sucess -->
        <!--                    <h4 class="page-header">R&eacute;sulat de votre S&eacute;l&eacute;ction</h4>-->
    </div> <!-- END col -->
</div> 