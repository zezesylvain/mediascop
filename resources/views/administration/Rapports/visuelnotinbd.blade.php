<?php
$lesvisuels = glob($dirc . "*");
$cond = " WHERE file = '%s'";
$sqlv = "SELECT * FROM ".\App\Helpers\DbTablesHelper::dbTable('DBTBL_RAPPORTS','db')." WHERE file = '%s'";
$thumbdir = $dirc . "thumbnail/";
$indic = 0;
?>
@foreach ($lesvisuels as $v)
    <?php
        $tab = pathinfo($v); //!array_key_exists($basename, $tabvisuels) &&
        $basename = $tab['basename'];
        ?>
    @if ($basename !== "thumbnail")
            <?php
                $extension = $tab['extension'];
                $filename = $tab['filename'];
                $filenamebis = $filename;
                $condition = sprintf($cond, $filename);
                $inc = 1;
                $newfilename = $cleanStr($filename) ;
            ?>

         @if($newfilename != $filename)
            <?php
                $coolname = str_replace($filename, $newfilename, $v) ;
                if(rename($v, $coolname)):
                    $filename = $newfilename ;
                endif;
            ?>
         @else
             @php($coolname = $v)
        @endif
        <?php
        $str = explode(DIRECTORY_SEPARATOR,$v);
        $coolnamebis = $str[count($str)-1];
        $sql = "SELECT COUNT(*) as nbRow FROM " .\App\Helpers\DbTablesHelper::dbTable('DBTBL_RAPPORTS','db') . $cond;
        $nbrows = \Illuminate\Support\Facades\DB::select(\Illuminate\Support\Facades\DB::raw($sql));
        while ($nbrows[0]->nbRow > 0):
            $filenamebis = "$filename-$inc";
            $inc++;
            $condition = sprintf($cond, $filenamebis);
        endwhile;
        $texte = str_replace('-', ' ', $filenamebis);
        $motcles = str_replace('-', ', ', $filenamebis);
        $newbasename = "$filenamebis.$extension";
        if($coolname != $dirc . $newbasename) :
            rename($coolname, $dirc . $newbasename);
        endif;

        ?>
        <div id="tab-{{$indic}}">
            <form id="essai" name="essai" method="post" action="{{route("rapport.validerFichier")}}" autocomplete="on">
                <input type="hidden" value="OK" name="formok">
                <input type="hidden" name="file" value="{{$newbasename}}">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-xs-6 col-md-2 form-group">
                        <label for="secteur" accesskey="s">Secteur d'activit&eacute;</label>
                        <select class="form-control" name="secteur" id="secteur">
                            @foreach ($dts as $rows)
                                <option value="{{$rows->id}}">{{utf8_decode($rows->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-5 form-group">
                        <label for="title"> Titre</label>
                        <input class="form-control" type="text" name="title" id="title" value="{{$texte}}">
                    </div>
                    <div class="col-xs-12 col-md-5 form-group">
                        <label for="motcles">Mots cl&eacute;s s&eacute;par&eacute;s par une virgule</label>
                        <input class="form-control" type="text" id="motcles" name="motcles" value="{{$motcles}}" placeholder="Mot1, mot 2, mot 3, ...">
                    </div>                <div class="col-xs-6 col-md-2 form-group">
                        <label for="periodicite" accesskey="p">P&eacute;riodicit&eacute;</label>
                        <select class="form-control" name="periodicite" id="periodicite">
                            @foreach ($periodicite as $rowp) :
                                <option value="{{$rowp['id']}}">{{utf8_decode($rowp['name'])}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-6 col-sm-4">
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="beginDate">DU :</label>
                                <input type="text" name="begindate{{$indic}}" id="beginDate" class="form-control"
                                       value="{{$begindate}}">
                            </div>
                            <div class="col-xs-6">
                                <label for="endDate">AU :</label>
                                <input type="text" name="enddate{{$indic}}" id="endDate" class="form-control" value="{{$enddate}}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <a id="urldel-{{$indic}}" title="Supprimer ce fichier" href="#urldel-{{$indic}}" onclick="sendData('fichier={{$coolnamebis}}', '{{route("ajax.delfile")}}','tab-{{$indic}}')">
                            <i class="fa fa-trash-o fa-3x rouge"></i>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-sm-3">
                        <button type="submit">Ajouter</button>
                    </div>
                </div>
            </form>
        </div>
        <hr class="trait-bleu">
        <?php
        $indic++;
        ?>
    @endif
@endforeach
