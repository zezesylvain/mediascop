@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Gestion des droits",2) !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col col-md-6 form-group">
                    <label for="profil"> Profil</label>
                    <select class="form-control chosen-select" name="profil" id="profil" onchange="javascript:location.href=this.value;">
                        @foreach ($profils as $row)
                            <?php $selected = $row['id'] == $profilID ? 'selected=selected' : '';?>
                            <option value="{{$row['id']}}" {{$selected}}>{{$row['name']}}</option>';
                        @endforeach
                    </select>
                </div>
            </div>
           
            <div class="row">
                <div class="col-sm-12">
                    <hr class="trait-bleu">
                    {!! $listeCheckbox !!}
                    <hr class="trait-bleu">
                </div>
                <div class="col-sm-12">
                    <label class="checkbox-inline">
                        <input type="checkbox" onChange="checkboxChecker('role',this.checked,'{{$nbreTotalRole}}');{{$cocheToutModif}} " {{$cocheToutChecked}}> <strong>cocher tout</strong>
                    </label>
                </div>
            </div>
        </div>
    </div>
@endsection
