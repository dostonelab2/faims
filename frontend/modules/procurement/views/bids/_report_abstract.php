<div class="print-container">
<?php
    $fin="";
    $x=0;
    $munit="";
    $tempid ="";
    $headerd = "";
    $itemno = "";
    $qty = "";
    $unit = "";
    $item_decription = "";
    $tablecount=count($columns);
    ?>
    <table border="0" width=100% style="border-collapse:collapse;">
      
        <tbody>
                <?php
                $max = $tablecount;
                $loops = 13 ;
                $i=7;
                foreach ($prdetails as $pr) {
                    $x++;
                    $tempid =  $pr[$columns[0]];
                    $headerid = $pr[$columns[1]];
                    $remarks = $pr[$columns[2]];
                    $itemno = $pr[$columns[3]];
                    $qty = $pr[$columns[4]];
                    $unit = $pr[$columns[5]];
                    $item_decription = $pr[$columns[6]];
                    echo '<tr class="nospace-border">';
                    echo '<td style="font-size: 9px; width: 5%; text-align: center;vertical-align: top;border:none;">'.$x.'</td>';
                    echo '<td style="font-size: 9px; width: 4%; text-align: center;vertical-align: top;border:none;">'.$qty.'</td>';
                    echo '<td style="font-size: 9px; width: 4%; text-align: center;vertical-align: top;border:none;">'.$unit.'</td>';
                    echo '<td style="font-size: 8px; width:25%;word-wrap: break-word;max-width:50px; text-align: left;vertical-align: top; border:none;padding-left:30px;">'.$item_decription.'</td>';
                    while($i<$loops) {
                        
                   
                        if($i>=$max && $i <=$loops) {

                            echo '<td style="font-size: 9px; width:10%;text-align: center; border:none;"></td>'; 

                        } else {

                            $myval = $pr[$columns[$i]];
                            if (is_numeric($myval)) {
                                $myval = number_format($myval,2);
                            }
                            if($myval=='0.00<br/>') {
                                $myval='No Bid';
                            }
                            if ($myval=='') {
                                $myval = "";
                            }
                             echo '<td style="font-size: 9px; width:10%;text-align: center; border:none;vertical-align:top;">'.$myval.'</td>'; 
                        }
           
                        $i++;              
                    }
                    $i=7;
                    echo '</tr>';
                }
                ?>  
        </tbody>
    </table>

</div>