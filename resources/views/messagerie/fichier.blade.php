<div class="col-sm-3 col-md-3 col-sm-3 col-xs-12">
    <div class="hpanel">
        <div class="panel-body file-body incon-ctn-view">
            <i class="fa fa-{{$fa}} text-info"></i>
        </div>
        <div class="panel-footer">
            <a href="{{route ('message.download',[$messageID, $filename])}}">{{$filename}}</a>
        </div>
    </div>
</div>
