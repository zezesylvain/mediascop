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
                    <input type="radio" {{ $checked}} name="campagne" value="{{ $rowvalue['id']}}" id="campagne[{{ $rowvalue['id']}}]" onchange="sendData('campagne='+this.value+'&media={{$media}}', '{{route ('ajax.getFormSaisieFrame')}}', 'frameitem');sendData('var=cid&k=0&val=' + this.value+'&action=cid', '{{route ('ajax.getDataInSession')}}', 'cid')">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<hr class="trait-bleu">
<div id="frameitem">
    <iframe src="{{route ('saisie.frameFormSaisie',[$datc[0]['id'],$media])}}" width="100%" height="700px"></iframe>
</div>
