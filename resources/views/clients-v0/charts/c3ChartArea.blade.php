<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="sparkline{{$nb}}-list responsive-mg-b-30">
        <div class="sparkline{{$nb}}-hd">
            <div class="main-spark{{$nb}}-hd">
                <h1><span class="c3-ds-n">{{$title}}</span></h1>
                <p>{{$description}}</p>
            </div>
            <button style="" class="btn btn-default" onclick="exportChartToPng('{{$chartID}}')"><i class="fa fa-upload"></i> Exporter</button>
        </div>
        <div class="sparkline{{$nb}}-graph">
            <div id="{{$chartID}}"></div>
        </div>
    </div>
</div>

