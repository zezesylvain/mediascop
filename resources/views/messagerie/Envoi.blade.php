@extends("layouts.admin")
@section("container")
    <style>
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
    
        .btn {
           /* border: 2px solid gray;
            color: gray;
            background-color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;*/
        }
    
        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
    </style>
    <div class="mailbox-compose-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="hpanel responsive-mg-b-30">
                        <div class="panel-body">
                            <a href="{{route ("message.envoi")}}" class="btn btn-success compose-btn btn-block m-b-md">Compose</a>
                            @include("messagerie.menu")
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="hpanel email-compose mg-b-15">
                        <div class="panel-heading hbuilt">
                            <div class="p-xs h4">
                                Nouveau message
                            </div>
                        </div>
                        <form method="post" class="form-horizontal" action="{{route ("message.store")}}" enctype="multipart/form-data">
                            {!! csrf_field () !!}
                            <div class="form-group chosen-select-single">
                                <label class="col-sm-1 control-label text-left" for="users">Pour:</label>
                                <div class="col-sm-11">
                                    <div class="chosen-select-single form-group">
                                        <select class="chosen-select form-control" multiple="multiple" name="users[]" id="users">
                                            @foreach($users as $user)
                                                <option value="{{$user['id']}}">{{$user['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label text-left">Sujet:</label>
                                <div class="col-sm-11">
                                    <input type="text" class="form-control input-sm" placeholder="Enter Message subject" name="objet">
                                </div>
                            </div>
                       
                            <div class="panel-heading hbuilt">
                                <div class="p-xs">
                                </div>
                            </div>
                            <div class="panel-body no-padding">
                                <textarea class="summernote6" name="texte"></textarea>
                                <hr>
                                <div id="fileList"></div>
                            </div>
                            
                            <div class="panel-footer">
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button class="btn btn-default"><i class="fa fa-edit"></i> Save</button>
                                        <button class="btn btn-default"><i class="fa fa-trash"></i> Discard</button>
                                    </div>
                                </div>
                                <button class="btn btn-primary ft-compse" type="submit">Envoyer message</button>
                                <div class="btn-group">
                                    <div class="upload-btn-wrapper">
                                        <button class="btn btn-default"><i class="fa fa-paperclip"></i></button>
                                        <input type="file" name="filesToUpload[]" id="filesToUpload" style="" multiple onchange="sendData('files='+this.value,'','fileList')"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        //get the input and UL list
        var input = document.getElementById('filesToUpload');
        var list = document.getElementById('fileList');
    
        //empty list for now...
        while (list.hasChildNodes()) {
            list.removeChild(ul.firstChild);
        }
                                                                  
        //for every file...
        for (var x = 0; x < input.files.length; x++) {
            //add to list
            var li = document.createElement('li');
            li.innerHTML = 'File ' + (x + 1) + ':  ' + input.files[x].name;
            list.append(li);
        }
    </script>
@endsection
