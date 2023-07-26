<div class="print-container">

<?php



    $fin="";
    $x=0;
   // $summary=0;
    $yy="";

    foreach ($prdetails as $pr) {

        $x++;
        $itemdescription = $pr["bids_item_description"];
        $quantity = $pr["bids_quantity"];
        $price = $pr["bids_price"];
        $units = $pr["bids_unit"];
        $totalcost =  $quantity * $price;
        if(strlen($itemdescription) > 1000){
            $input = $itemdescription;
            $len = strlen($input);
            $space_br = strrpos($input,"<br />",-$len/2);
            if($space_br == true){
                $space = $space_br;
            }else{
                $space = strrpos($input," ",-$len/2);
            } 
            $itemdescription1 = substr($input,0,$space);
            $itemdescription2  = substr($input,$space);

            $append = "<tr>";
            $append = $append . "<td width='10%' style='vertical-align: top;padding-left:px;text-align:center;'>".$x.".</td>";
            $append = $append . "<td width='10%' style='vertical-align: top;padding-left:25px;text-align:center;'>".$units."</td>";
            $append = $append . "<td autosize='0' width='40%' style='vertical-align: top;padding:20px;padding-top:0px;font-size:12px;word-wrap: break-word;'>" . $itemdescription1 . "</td>";
            $append = $append . "<td width='13%' style='vertical-align: top;font-size:12px;padding-left:75px;text-align:left;'>" . $quantity . "</td>";
            $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($price,2) . "</td>";
            $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($totalcost,2) . "</td>";
            $append = $append . "</tr>";

            $append2 = "<tr>";
            $append2 = $append2 . "<td width='10%' style='vertical-align: top;padding-left:px;text-align:center;'></td>";
            $append2 = $append2 . "<td width='10%' style='vertical-align: top;padding-left:25px;text-align:center;'></td>";
            $append2 = $append2 . "<td autosize='0' width='40%' style='vertical-align: top;padding:20px;padding-top:0px;font-size:12px;word-wrap: break-word;'>" . $itemdescription2 . "</td>";
            $append2 = $append2 . "<td width='13%' style='vertical-align: top;font-size:12px;padding-left:75px;text-align:left;'></td>";
            $append2 = $append2 . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'></td>";
            $append2 = $append2 . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'></td>";
            $append2 = $append2 . "</tr>";
            $fin = $fin . $append . $append2;


        }else{
            $append = "<tr>";
            $append = $append . "<td width='10%' style='vertical-align: top;padding-left:px;text-align:center;'>".$x.".</td>";
            $append = $append . "<td width='10%' style='vertical-align: top;padding-left:25px;text-align:center;'>".$units."</td>";
            $append = $append . "<td autosize='0' width='40%' style='vertical-align: top;padding:20px;padding-top:0px;font-size:12px;word-wrap: break-word;'>" . $itemdescription . "</td>";
            $append = $append . "<td width='13%' style='vertical-align: top;font-size:12px;padding-left:75px;text-align:left;'>" . $quantity . "</td>";
            $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($price,2) . "</td>";
            $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($totalcost,2) . "</td>";
            $append = $append . "</tr>";
            $fin = $fin . $append;
        }

        // $append = "<tr>";
        // $append = $append . "<td width='10%' style='vertical-align: top;padding-left:px;text-align:center;'>".$x.".</td>";
        // $append = $append . "<td width='10%' style='vertical-align: top;padding-left:25px;text-align:center;'>".$units."</td>";
        // $append = $append . "<td autosize='0' width='40%' style='vertical-align: top;padding:20px;padding-top:0px;font-size:12px;word-wrap: break-word;'>" . $itemdescription . "</td>";
        // $append = $append . "<td width='13%' style='vertical-align: top;font-size:12px;padding-left:75px;text-align:left;'>" . $quantity . "</td>";
        // $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($price,2) . "</td>";
        // $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($totalcost,2) . "</td>";
        // $append = $append . "</tr>";
        // $fin = $fin . $append;
    }


    ?>  

   <table autosize="0">
    
    <tbody>
            <?php
                echo $fin;
            ?>
    </tbody>
    </table>   

</div>