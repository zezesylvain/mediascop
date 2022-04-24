@extends("layouts.admin")
@section("container")
<div class="mailbox-view-area mg-tb-15">
    @inject('msg', '\App\Http\Controllers\Messagerie\MessagerieController')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-md-3 col-sm-3 col-xs-12">
                <div class="hpanel responsive-mg-b-30">
                    <div class="panel-body">
                        <a href="{{route ('message.envoi')}}" class="btn btn-success compose-btn btn-block m-b-md">Compose</a>
                        @include("messagerie.menu")
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-md-9 col-sm-9 col-xs-12">
                <div class="hpanel email-compose mailbox-view mg-b-15">
                    <div class="panel-heading hbuilt">
                        
                        <div class="p-xs h4">
                            <small class="pull-right">
                                {{date ("H:i")}} (16 hours ago)
                            </small> <b>Consulter Message</b>
                        
                        </div>
                    </div>
                    <div class="border-top border-left border-right bg-light">
                        <div class="p-m custom-address-mailbox">
                            
                            <div>
                                <span class="font-extra-bold">Objet: </span> {{$message[0]['objet']}}
                            </div>
                            <div>
                                <span class="font-extra-bold">De: </span>
                                <a href="#">{{$destinataire = $getChampTable ($getTableName ($dbTable ('DBTBL_USERS')),$message[0]['user'])}}</a>
                            </div>
                            <div>
                                <span class="font-extra-bold">Date: </span> {{$date2Fr ($message[0]['created_at'],"d.m.Y")}}
                            </div>
                        </div>
                    </div>
                    <div class="panel-body panel-csm">
                        <div>
                            {!! $message[0]['texte'] !!}
                        </div>
                    </div>
                    
                    <div class="border-bottom border-left border-right bg-white mg-tb-15">
                        @if($msg->chercherPieceJointe($message[0]['id']))
                        <p class="m-b-md">
                            <span><i class="fa fa-paperclip"></i> {{$msg->getNbrePiecesJointes($message[0]['id'])}} pièce(s) jointe(s) - </span>
                            <a href="{{route ('message.downloadZip',[$message[0]['id']])}}" class="btn btn-default btn-xs">Télécharger tous en format zip <i class="fa fa-file-zip-o"></i> </a>
                        </p>
                        @endif
                        
                        <div>
                            <div class="row">
                                {!! $msg->getPiecesJointes ($message[0]['id']) !!}
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="panel-footer text-right">
                        <div class="btn-group">
                            <a href="{{route ('message.reply',[$messageID])}}" class="btn btn-default"><i class="fa fa-reply"></i> Répondre</a>
                            <button class="btn btn-default"><i class="fa fa-arrow-right"></i> Transférer</button>
                            <button class="btn btn-default"><i class="fa fa-print"></i> Archiver</button>
                            <button class="btn btn-default"><i class="fa fa-trash-o"></i> Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
