@php
    $c3Data[$chartID] = [
        "title"=>"{$title}",
        "description"=>"{$description}",
        "datas" =>$datas,
            "type"=>"bar",
            "groupe"=> true,
            "labels"=>true,
        "x" => $x,
        "axis" => "category"
    ] ;
@endphp
