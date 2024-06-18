@extends("layouts.admin")
@section("container")
    <form action="" method="post">
        <fieldset>
            <h3>Les Profils</h3>
            {{csrf_field()}}
            <input name="formok" type="hidden" value="OK" />
            <div class="row">
                <div class="col col-md-6 form-group">
                    <label for="profil"> Profil</label>
                    <select class="form-control" name="profil" id="profil" onchange="javascript:location.href=this.value;">
                        @foreach ($profils as $row)
                            <?php $selected = $row['id'] == $profilID ? 'selected=selected' : '';?>
                            <option value="{{$row['id']}}" {{$selected}}>{{$row['name']}}</option>';
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12"><hr>
                    <h3>Gestion des droits</h3>
                    <h4 id="divInutile"></h4>
                    {!! $listeCheckbox !!}
                </div>
                <br>
                <div class="col-sm-6">
                    <label class="checkbox-inline">
                        <input type="checkbox" onChange="checkboxChecker('role',this.checked,'{{$nbreTotalRole}}');{{$cocheToutModif}} " {{$cocheToutChecked}}> <strong>cocher tout</strong>
                    </label>
                </div>
            </div>
        </fieldset>
    </form>
@endsection