@extends("layouts.admin")
@section("container")
    {!! $titreHtml("Gestion des droits",2) !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-sm-4 form-group">
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
                    @foreach($men as $grpMenu => $roles)
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="panel panel-primary hpanel responsive-mg-b-30">
                                <div class="panel-heading" style="font-size: 13pt;">
                                    <?php
                                        $iconeGrpM = $getChampTable($dbTable('DBTBL_ICONES'),$getChampTable($dbTable('DBTBL_GROUPEMENUS'),$grpMenu,'icone'));
                                    ?>
                                    <i class="fa fa-{{$iconeGrpM}}"></i> {{ $getChampTable($dbTable('DBTBL_GROUPEMENUS'),$grpMenu) }}
                                </div>
                                <div class="panel-body" style="height: 150px;overflow: auto;">
                                    @foreach($roles as $role)
                                        @php($checked = array_key_exists('check',$role) ? 'checked = "checked"' : '')
                                        <label for="role[{{$role['role']}}]" class="checkbox-inline" style="bottom: 3px;font-size: 12pt;">
                                            <input {{$checked}}  name="role[]" id="role[{{$role['role']}}]" type="checkbox" value="{{$role['role']}}" onclick="checkRole('{{$profilID}}','{{$role['role']}}',this.checked)">
                                            {{ $role['name'] }}
                                        </label>
                                        <br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkRole(profilID,roleID,etat)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const url = "{{ route('ajax.checkRoleToProfil') }}";
            var datas = {
                profilID : profilID,
                roleID : roleID,
                etat : etat
            };
            $.ajax({
                url: url,
                method: 'POST',
                data: datas,
                dataType: "JSON",
                success: function (resultat) {
                    ui.notify("Alerte Notification",resultat.message,).closable().hide(5000).effect('slide');
                }
            })
        }
    </script>

@endsection