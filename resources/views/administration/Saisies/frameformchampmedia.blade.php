<hr class="trait-bleu">
<div class="static-table-list">
    <table class="table table-responsive table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th width="20%">Format</th>
                <th width="20%">Nature</th>
                <th width="15%">Cible</th>
                @if($offretelecom != null)
                    <th width="20%">Offre t&eacute;l&eacute;com</th>
                @endif
                @foreach ($leschampsdumedia as $ch => $rowch)
                   <th width="75px"> {{ ucfirst($ch) }} </th>
                @endforeach
                <th width="10px"> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $increment = 0;
            ?>
            @foreach($datc as $rowvalue)
            <tr>
                <?php
                $increment++;
                $checked = $increment == 1 ? " checked=\"checked\"" : "";
                $checked = isset($campagne) && $campagne == $rowvalue['id'] ? " checked=\"checked\"" : $checked;
                ?>
                <td>
                    <label for="campagne[{{ $rowvalue['id']}}]">
                        {{ $getChampTable($tformat,$rowvalue['format'])}}
                    </label>
                </td>
                <td>
                    <label for="campagne[{{ $rowvalue['id']}}]">
                        {{ $getChampTable($tnature,$rowvalue['nature'])}}
                    </label>
                </td>
                <td>
                    <label for="campagne[{{ $rowvalue['id']}}]">
                        {{ $getChampTable($tcible,$rowvalue['cible'])}}
                    </label>
                </td>
                @if($offretelecom != null)
                    <td>
                        <label for="campagne[{{ $rowvalue['id']}}]">
                            {{ ($offretelecom)}}
                        </label>
                    </td>
                @endif
                @if($media != 1 && $media != 2)
                    @foreach ($leschampsdumedia as $ch => $rowch)
                            <td>
                                <label for="campagne[{{ $rowvalue['id']}}]">
                                     {{$getChampTable($ch.'s',$rowvalue[$ch])}}
                                </label>
                            </td>
                    @endforeach
                @else
                        <td>
                            <label for="campagne[{{ $rowvalue['id']}}]">
                                {{$rowvalue['duree']}}
                            </label>
                        </td>
                @endif
                <td>
                    <input type="radio" {{ $checked}} name="campagne" value="{{ $rowvalue['id']}}" id="campagne[{{ $rowvalue['id']}}]" onchange="sendData('var=cid&k=0&val=' + this.value+'&action=cid', '{{route ('ajax.getDataInSession')}}', 'cid');">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<hr class="trait-bleu">
<div class="row">
    <div class="col-xs-4 col-sm-3 col-md-2">
        <div id="dateBlog">
            <label for="date">Date:</label>
            <select name="date" id="date" onchange="sendData('plus=1&dt='+this.value+'&action=changeDate', '{{route ('ajax.changeDate')}}', 'dateBlog'); sendData('var=date&val='+this.value+'&action=cid', '{{route('ajax.addInputMedia')}}', 'cid')" class="form-control">
                <?php
                for ($j = 0; $j <= 30; $j++):
                    $temps = time() - $j * 60 * 60 * 24;
                    $dateV = date("Y-m-d", $temps);
                    $dateO = date("d-m-Y", $temps);
                    echo "<option value=\"$dateV\">$dateO</option>";
                endfor;
                ?>
                <option value="YES">Plus loin</option>
            </select>
        </div>
    </div>
    <div id=""></div>
</div>
<hr class="trait-bleu">
<div class="row">
    <div class="col-xs-12" id="addinput">
        {!! \App\Http\Controllers\Administration\SaisieController::createInputMedia ($media) !!}
    </div>
    <?php
    for ($ji = 2; $ji <= 10; $ji++):
        echo '<div class="col-xs-12" id="addinput' . $ji . '"> </div>';
    endfor;
    ?>
</div>
<hr class="trait-bleu">
<div class="row">
    <div class="col-xs-12">
        {!! \App\Http\Controllers\core\FormController::champSubmit("Valider") !!}
    </div>
</div>

