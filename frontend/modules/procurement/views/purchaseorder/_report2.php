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
        $append = "<tr>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left:px;text-align:center;'>".$x.".</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left:25px;text-align:center;'>".$units."</td>";
        $append = $append . "<td autosize='0' width='40%' style='vertical-align: top;padding:20px;padding-top:0px;font-size:11px;word-wrap: break-word;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='13%' style='vertical-align: top;font-size:12px;padding-left:75px;text-align:left;'>" . $quantity . "</td>";
        $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($price,2) . "</td>";
        $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:25px;text-align:center;padding-right:10px;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }


    ?>  

   <table autosize="0" style="page-break-inside:avoid;">
    
    <tbody style="">
            <?php
                echo $fin;   
            ?>
    </tbody>
    </table>   

</div>