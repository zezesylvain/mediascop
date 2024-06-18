@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Opérations") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="all-form-element-inner">
                        <form class="form-group-inner" role="form" method="POST" action="{{ route('saisie.storeOperations') }}">
                            {{ csrf_field() }}
                            @isset($operation)
                                <input type="hidden" name="id" value="{{$operation[0]['id']}}">
                            @endisset
                            <div class="col-sm-6 form-group{{ $errors->has('name') ? '
                            has-error' : '' }}">
                                <label for="name">Titre de l' Opération</label>
                                <input id="name" placeholder="" type="text" class="form-control" name="name" value="{{ $operation[0]['name'] ?? old('name') }}"  autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                            
                            <div class="col-sm-6 form-group{{ $errors->has('annonceur') ? ' has-error' : '' }}">
                               @include('administration.Operations.form-champ-select2', [
                                    'champ' => 'annonceur',  'data' => $annonceurs, 
                                    'selectedValue' => $operation[0]['annonceur'] ?? 0,
                                    'ajaxOnchange' => 'onchange="getTypeService(this.value)"'
                                ])
                            </div>
                            
                            <div class="col-sm-6 col-md-3 form-group{{ $errors->has('couverture') ? ' has-error' : '' }}">
                               @include('administration.Operations.form-champ-select2', [
                                    'champ' => 'couverture',  'data' => $couvertures, 
                                    'selectedValue' => $operation[0]['couverture'] ?? 0
                                ])
                            </div>
                            <div class="col-sm-6 col-md-3 form-group{{ $errors->has('type_service') ? ' has-error' : '' }}">
                                @include('administration.Operations.form-champ-select2', [
                                    'champ' => 'type_service', 'libelle' => 'Type de service',
                                    'data' => $typeservices, 'selectedValue' => $operation[0]['type_service'] ?? 0
                                ])
                            </div>

                            <div class="col-sm-6 col-md-3 form-group{{ $errors->has('adddate') ? ' has-error' : '' }}">
                                <label for="adddate">Date ajout</label>
                                <input id="adddate" placeholder="" type="text" class="form-control avantDate" name="adddate" value="{{ $operation[0]['adddate'] ?? $dateajout }}"  autofocus>
                                @if ($errors->has('adddate'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('adddate') }}</strong>
                                </span>
                                @endif
                            </div>
    
                            {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
                        </form>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr class="trait-bleu">
                    {!! $donnees !!}
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function getTypeService(annonceurID) {
            $.ajax({
                type : "POST",
                url : '{{ route('ajax.getTypeService') }}' ,
                dataType: 'JSON',
                data : {
                    annonceurID : annonceurID,
                },

                success : function(data){
                    $('#type_service').empty().append("<option value=''>Choisir un type de service</option>");
                    $.map( data.typeServices, function( item) {
                        $('#type_service').append("<option value="+item.id+">"+item.name+ "</option>");
                    });
                    $('#type_service').trigger("chosen:updated");
                }
            });
        }
    </script>
@endsection
