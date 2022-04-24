@extends("layouts.admin")
@section("container")
    @inject('message', '\App\Http\Controllers\Messagerie\MessagerieController')
    
<div class="mailbox-area mg-tb-15">
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
                <div class="hpanel mg-b-15">
                    <div class="panel-heading hbuilt mailbox-hd">
                        <div class="col-sm-12">
                            @if(isset($k))
                                <h3>Message Envoyé</h3>
                            @else
                                <h3>Boîte de reception</h3>
                            @endif
                            <hr class="trait-bleu">
                        </div>
    
                        <div class="text-center p-xs font-normal">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" placeholder="Search email in your inbox..."> <span class="input-group-btn"> <button type="button" class="btn btn-sm btn-default">Search
											</button> </span></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 mg-b-15">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Refresh</button>
                                    <button class="btn btn-default btn-sm"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-default btn-sm"><i class="fa fa-exclamation"></i></button>
                                    <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-default btn-sm"><i class="fa fa-check"></i></button>
                                    <button class="btn btn-default btn-sm"><i class="fa fa-tag"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 mailbox-pagination mg-b-15">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i></button>
                                    <button class="btn btn-default btn-sm"><i class="fa fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-mailbox">
                                <tbody>
                                @foreach($messagesRecus as $r)
                                    @php
                                        if (!isset($k)):
                                            $unread = $r['etat_message'] == 1 ? "unread" : "";
                                        endif;
                                        $destinataire = $getChampTable ($getTableName ($dbTable ('DBTBL_USERS')),$r['user']);
                                        $link = !$message->chercherPieceJointe($r['id']) ? '' : "<i class='fa fa-paperclip'></i>";
                                        @endphp
                                <tr class="{{$unread ?? ''}}">
                                    <td class="">
                                        <div class="checkbox checkbox-single checkbox-success">
                                            <input type="checkbox" >
                                            <label></label>
                                        </div>
                                    </td>
                                    <td><a href="{{route ('message.showMessage',$r['id'])}}">{{$destinataire}}</a></td>
                                    <td><a href="{{route ('message.showMessage',$r['id'])}}">{{$r['objet']}}</a>
                                    </td>
                                    <td>{!! $link !!}</td>
                                    <td class="text-right mail-date">{{$date2Fr ($r['created_at'])}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <i class="fa fa-eye"> </i> {!! $message->messageNonLus() !!} Non lus
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
