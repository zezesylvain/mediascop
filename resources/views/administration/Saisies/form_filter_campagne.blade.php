<div class="container-fluid">
    <form id="essai" name="essai" method="post" action="{{route('saisie.filterCampagne', $action)}}" autocomplete="on">
        {{ csrf_field() }}
        <input type="hidden" id="formok" name="filteroperation" value="ok">
       
        <div class="row">
            <div class="col-sm-2 form-group">
                <div class="date-picker-inner">
                    <div class="form-group data-custon-pick" id="">
                        <label for="ddebutop">D&eacute;but:</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control avantDate" name="dddebutfop" value="{{$debutop}}" id="ddebutop" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 form-group">
                <div class="date-picker-inner">
                    <div class="form-group data-custon-pick" id="">
                        <label for="ddfinop">Fin:</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control avantDate" name="ddfinfop" value="{{$finop}}" id="ddfinop" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="chosen-select-single mg-b-20 form-group">
                    <label  for="secteurfilteroperation">Secteur</label>
                    <select name="secteurfilteroperation" id="secteurfilteroperation" data-placeholder="" class="chosen-select form-control" tabindex="-1" onchange="chercherAnnonceur(this.value)">
                        <option value="0">Choisir un secteur</option>
                        @foreach($listeDesSecteurs as $r)
                            @php($selected = $r['id'] == $secteurop ? "selected='selected'" : "")
                            <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="chosen-select-single mg-b-20" id="annonceurfilteroperationitem">
                    <label  for="annonceurfilteroperation">Annonceur</label>
                    <select name="annonceurfilteroperation" id="annonceurfilteroperation" data-placeholder="" class="chosen-select select2_demo_2 form-control" tabindex="-1" onchange="chercherOperations(this.value)">
                        <option value="0">Choisir un annonceur</option>
                        @foreach($listeDesAnnonceurs as $r)
                            @php($selected = $r['id'] == $annonceurop ? "selected='selected'" : "")
                            <option value="{{$r['id']}}" {{$selected}}>{{$r['raisonsociale']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="chosen-select-single mg-b-20 form-group" id="opfilteroperationitem">
                    <label  for="opfilteroperation">Operations</label>
                    <select name="opfilteroperation" id="opfilteroperation" data-placeholder="Choisir une opération" class="chosen-select form-control" tabindex="-1">
                        <option value="0">Choisir une opération</option>
                        @foreach($listeDesOperations as $r)
                            @php($selected = $r['id'] == $operationop ? "selected='selected'" : "")
                            <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-2 btn-filtre">
                <button type="submit" class="btn btn-custon-four btn-primary btn-block">Filtrer</button>
            </div>
        </div>
    </form>
</div>

<script>
    function chercherAnnonceur(secteurID) {
        var url = "{{route ('ajax.listSelectAnnonceur')}}";
        $.ajax({
            type : "POST",
            url : url ,
            dataType: 'JSON',
            data : {
                secteur : secteurID
            },
            
            success : function(result){
                $('#annonceurfilteroperation').empty().append('<option value="">Choisir un annonceur</option>');
                
                $.map( result, function( item ) {
                    $('#annonceurfilteroperation').append('<option value="'+item.id+'">' + item.raisonsociale + '</option>');
                });
                $('#annonceurfilteroperation').trigger("chosen:updated");
                
            }
            
        });
    }
    
    function chercherOperations(annonceurID) {
        var url = "{{route ('ajax.listSelectOperation')}}";
        $.ajax({
            type : "POST",
            url : url ,
            dataType: 'JSON',
            data : {
                annonceur : annonceurID
            },
            
            success : function(result){
                $('#opfilteroperation').empty().append('<option value="">Choisir une opération</option>');
                $.map( result, function( item ) {
                    $('#opfilteroperation').append('<option value="'+item.id+'">' + item.name + '</option>');
                });
                $('#opfilteroperation').trigger("chosen:updated");
            }
            
        });
    }

</script>
