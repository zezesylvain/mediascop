<?php
     echo "<div class='panel panel-primary'>".$marker['docampagne']."</div>";
?>

echo "<div class='panel panel-primary'>
<div class='panel-heading'><h5 class='panel-title'> Infos Panneaux</h5></div>
<div class='panel-body'><div class='row'><div class='col-sm-4'>
            <img src='".$marker['docampagne']."' class='img-thumbnail' style='width: 100%;height: auto;'></div><div class='col-sm-8'><p><strong>Campagne:</strong> ".$marker['titre']."<br><strong>Annonceur:</strong>".$marker['annonceur']."<br><strong>Localité:</strong> ".$marker['localite']."<br><strong> Code:</strong> ".$marker['code']."<br><strong> Regie:</strong> ".$marker['regie']."<br><strong> Nature:</strong>".$marker['nature']."<br>
                <strong> Format:</strong> ".$marker['format']."<br></p></div></div></div><div class='panel-footer'><a class='btn btn-primary btn-xs' title='voir plus !' href='".$marker['url']."'><i class ='fa fa-search'></i></a></div></div>";
