<tr>
    <td>{{$rowID}}</td>
    @if(!$rowvalide)
        <td>{!!$rowdate!!}</td>
    @else
        <td>{!!$date2Fr ($rowdate)!!}</td>
    @endif
    @if($action != "HORS-MEDIAJJ")
        {!! $tdDuree !!}
    @endif
    @if(!$rowvalide)
        <td>{!!$supportID!!}</td>
    @else
        <td>{!!$rowsupport!!}</td>
    @endif
    <td>{!! $campagnetitle !!}</td>
    {!! $tdHorsMedia !!}
    @if($action != "HORS-MEDIA")
        <td>{!! $rowtarif !!}</td>
        <td>{!! $rowcoeff !!}</td>
    @endif
    <td>{!! $userSaisie !!}</td>
    {!! $tdErreur !!}
    <td>
        @if(!$rowvalide)
            <div id="validebox{{$rowID}}">
                <input id="listpub[{{$ij}}]" type="checkbox" name="listpub[]" value="{{$rowID}}"><br>
                <a href="#plisting" title="Valider la pub" onclick="sendData('database={{$DATABASE}}&id={{$rowID}}', '{{$routeval}}', 'validebox{{$rowID}}');" style="color: #0000cc;">
                    <i class="fa fa-check-circle"></i>
                </a>
            </div>
        @else
            @include("administration.TableauDeBords.checkedPubs")
        @endif
    </td>
</tr>

<script>
    function signalerErreur(pubID,etat) {
        var url = "{{route ('ajax.signalerErreurPub')}}";
        $.ajax({
            type : "GET",
            url : url ,
            data : {
                pubID: pubID,
                etat: etat
            },

            success : function(result){

            }
        });
    }
</script>
