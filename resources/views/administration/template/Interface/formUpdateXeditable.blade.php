<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="sparkline9-list mt-b-30">
        <div class="sparkline9-hd">
            <div class="main-sparkline9-hd">
                <h1>Modifier La Ligne</h1>
            </div>
        </div>
        <div class="sparkline9-graph">
            <div class="static-table-list">
                <table class="table sparkle-table">
                    <tbody>
                    @foreach($champsTable as $champ)
                    
                    <tr>
                        <td>{{$champ['Field']}}</td>
                        <td>
                            <span id="sparkline1">
                        @if($is_displayable($table, $champ['Field']))
                            @if(!$is_inlinable($table, $champ['Field']))
                                {{ $ec($ligne[0][$champ['Field']], $champ['Field']) }}
                            @else
                                <a href="#{{$champ['Field']}}" class="x-edit" data-table="{{$table}}" id="{{$champ['Field']}}" data-pk="{{$pk}}" data-title="{{"Modifier {$champ['Field']}"}}">
                                {{$ligne[0][$champ['Field']]}}
                                </a>
                            @endif
                        @endif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
