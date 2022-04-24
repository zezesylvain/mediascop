@if(isset($c3Data))
    <script>
        (function ($) {
            "use strict";
            
            <?php
            $names = "";
            $columns = "";
            $colors = "";
            $vi  = "" ;
            $groups = [] ;
            foreach($c3Data AS $key => $dt):
            //$groups = [] ;
            $i = 1 ;
            foreach($dt["datas"] AS $dtitem):
                $columns .=  $vi."['data$i', ". join(", ", $dtitem["data"]). "]
                         ";
                $colors .= $vi."data$i: '$dtitem[color]'";
                $names .= $vi."data$i: '$dtitem[name]'";
                $vi = ", " ;
                $groups[] = "data$i" ;
                $i++ ;
            endforeach;
            $grp = join("', '", $groups) ;
            $xt = "" ;
            $cx = "" ;
            if(array_key_exists("x", $dt)):
                $xt = "x: 'x',
                         " ;
                $cx = "['x', '". join("', '", $dt["x"]). "'],
                             ";
            endif;
            if(array_key_exists("axis", $dt)):
                $xt = "x: 'x',
                         " ;
                $cx = "['x', '". join("', '", $dt["x"]). "'],
                             ";
            endif;
            ?>
            c3.generate({
                bindto: '#<?php echo $key;?>',
                data:{
                    <?php echo $xt ; ?>
                    columns: [
                        <?php echo $cx ; ?>
                        <?php echo $columns;?>
                    ],
                    names:{
                        <?php echo $names;?>
                    },
                    colors:{
                        <?php echo $colors;?>
                    }<?php
                    if(array_key_exists("type", $dt)):
                        echo ", type: '$dt[type]'" ;
                        if(array_key_exists("labels", $dt)):
                            echo ", labels: " ;
                            if($dt['labels']):
                                echo "true";
                            else:
                                echo "false";
                            endif;
                        endif;
                        if (($dt["type"] == "bar") AND $dt["groupe"]):
                            echo ",
                                groups: [
                            ['$grp']
                                ]
                                 " ;
                        endif;
                    endif;
                    ?>
                }
                <?php
                if(array_key_exists("axis", $dt)):
                    echo ",
                            axis: {
                                x: {
                                    type: '$dt[axis]'
                                }
                            }" ;
                endif;
                
                ?>
            });
            <?php
            $columns = "";
            $colors = "";
            $names = "";
            $vi  = "" ;
            $groups = [] ;
            endforeach;
            ?>
        })(jQuery);

        function exportChartToPng(chartID){
//fix weird back fill
            d3.select('#'+chartID).selectAll("path").attr("fill", "none");
//fix no axes
            d3.select('#'+chartID).selectAll("path.domain").attr("stroke", "black");
//fix no tick
            d3.select('#'+chartID).selectAll(".tick line").attr("stroke", "black");
            var svgElement = $('#'+chartID).find('svg')[0];
            saveSvgAsPng(svgElement, chartID+'.png');
        }
    </script>
@endif
