@extends("layouts.admin")
@section("contenu")
    <div class="x-editable-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="x-editable-list">
                        <div class="alert-title">
                            <h2>X-editable</h2>
                        </div>
                        <table id="user" class="table table-bordered table-striped x-editor-custom">
                            <thead>
                            <tr>
                                <th style="width=35%">Nom et Prenoms</th>
                                <th style="width=65%">Email</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($route = route ('updatexEditable'))
                            @php($i = 0)
                            @foreach($datas as $r)
                                @php($i++)
                                <tr>
                                    <td>
                                        {!! \App\Http\Controllers\core\TemplateController::xEditable ("emails","{$r->name}","$route","name","text",$r->id,"bottom","","Entrer le nom utilisateur") !!}
                                    </td>
                                    <td>
                                        {!! \App\Http\Controllers\core\TemplateController::xEditable ("emails","{$r->email}","$route","email","text",$r->id,"bottom","","Entrer un email") !!}
                                    </td>
                                </tr>
                            @endforeach

                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#name').editable({
                                        url:'{{route('updatexEditable')}}',
                                        title:'Nom',
                                        type:'text',
                                        pk: 1,
                                        validate: function(value) {
                                            var current_pk = $ (this) .data ();
                                            console.log(current_pk);
                                            if($.trim(value) == '') {
                                                return 'Value is required.';
                                            }
                                        },
                                    });
                                })

                            </script>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#email').editable({
                                        url:'{{route('updatexEditable')}}',
                                        title:'Email',
                                        type:'email',
                                        pk: 1
                                    });
                                })

                            </script>

                            {{--
							 <tr>
								 <td>Empty text field, required</td>
								 <td>
									 <a href="#" id="firstname" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter your firstname"></a>
								 </td>
							 </tr>
							 <tr>
								 <td>Select, local array, custom display</td>
								 <td>
									 <a href="#" id="sex" data-type="select" data-pk="1" data-value="" data-title="Select sex"></a>
								 </td>
							 </tr>
							 <tr>
								 <td>Select, remote array, no buttons</td>
								 <td><a href="#" id="group" data-type="select" data-pk="1" data-value="5" data-source="/groups" data-title="Select group">Admin</a>
								 </td>
							 </tr>
							 <tr>
								 <td>Select, error while loading</td>
								 <td><a href="#" id="status" data-type="select" data-pk="1" data-value="0" data-source="/status" data-title="Select status">Active</a>
								 </td>
							 </tr>
							 <tr>
								 <td>Datepicker</td>
								 <td>
									 <a href="#" id="vacation" data-type="date" data-viewformat="dd.mm.yyyy" data-pk="1" data-placement="right" data-title="When you want vacation to start?">25.02.2013</a>
								 </td>
							 </tr>
							 <tr>
								 <td>Combodate (date)</td>
								 <td>
									 <a href="#" id="dob" data-type="combodate" data-value="1984-05-15" data-format="YYYY-MM-DD" data-viewformat="DD/MM/YYYY" data-template="D / MMM / YYYY" data-pk="1" data-title="Select Date of birth"></a>
								 </td>
							 </tr>
							 <tr>
								 <td>Combodate (datetime)</td>
								 <td>
									 <a href="#" id="event" data-type="combodate" data-template="D MMM YYYY  HH:mm" data-format="YYYY-MM-DD HH:mm" data-viewformat="MMM D, YYYY, HH:mm" data-pk="1" data-title="Setup event date and time"></a>
								 </td>
							 </tr>
							 <tr>
								 <td>Bootstrap Datetimepicker</td>
								 <td><a href="#" id="meeting_start" data-type="datetime" data-pk="1" data-url="/post" data-placement="right" title="Set date & time">15/03/2013 12:45</a>
								 </td>
							 </tr>
							 <tr>
								 <td>Textarea, buttons below. Submit by <i>ctrl+enter</i>
								 </td>
								 <td><a href="#" id="comments" data-type="textarea" data-pk="1" data-placeholder="Your comments here..." data-title="Enter comments">awesome user!</a>
								 </td>
							 </tr>
							 <tr>
								 <td>Checklist</td>
								 <td>
									 <a href="#" id="fruits" data-type="checklist" data-value="2,3" data-title="Select fruits"></a>
								 </td>
							 </tr>
							 <tr>
								 <td>Select2 (tags mode)</td>
								 <td><a href="#" id="tags" data-type="select2" data-pk="1" data-title="Enter tags">html, javascript</a>
								 </td>
							 </tr>
							 <tr>
								 <td>Select2 (dropdown mode)</td>
								 <td>
									 <a href="#" id="country" data-type="select2" data-pk="1" data-value="BS" data-title="Select country"></a>
								 </td>
							 </tr>
							 --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
