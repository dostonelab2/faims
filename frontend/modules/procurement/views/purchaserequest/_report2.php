<div class="print-container" autosize="0">
    <?php
    $fin="";
    $x=0;
    foreach ($prdetails as $pr) {
        $x++;
        if ($x==1) { $unit = $pr["name_short"]; } else { $unit = $pr["name_long"]; }
        $itemdescription = $pr["purchase_request_details_item_description"];
        //$itemspecification = $pr["purchase_request_details_item_specification"];
        $quantity = $pr["purchase_request_details_quantity"];
        $price = $pr["purchase_request_details_price"];
        $totalcost =  $quantity * $price;

        if ($price=='0.00' || $price == null) {
            $price = "";
            $totalcost = "";
        }else{
            $totalcost = number_format($totalcost,2) ;
            $price = number_format($price, 2);
        }
        $append = "<tr style='vertical-align: middle;'>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;'></td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:5px;'>".$unit."</td>";
        $append = $append . "<td width='50%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:5px;padding-right:5px;overflow:none;'>". $itemdescription ."</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;padding-right:0px;text-align:center;'>" . $quantity . "</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;padding-right:0px;'>" . $price . "</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;'>" . $totalcost . "</td>";
        $append = $append . "</tr>";

        
        $append2 = "<tr style='vertical-align: middle;'>";
        $append2 = $append2 . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;'></td>";
        $append2 = $append2 . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:5px;'></td>";
        $append2 = $append2 . "<td width='50%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:5px;padding-right:5px;overflow:none;'></td>";
        $append2 = $append2 . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;padding-right:0px;text-align:center;'></td>";
        $append2 = $append2 . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;padding-right:0px;text-align:right;'><b>Total:</b></td>";
        $append2 = $append2 . "<td width='10%' style='vertical-align: top;padding:10px;font-size:11px;padding-left:0px;text-decoration:underline;'><b>" . $totalcost . "</b></td>";
        $append2 = $append2 . "</tr>";

        $fin = $fin . $append . $append2;
        
    }   

    ?>

    <table width="100%">
        <tbody>
        <?php   
        echo $fin;
        ?>
        </tbody>
    </table>

</div>
