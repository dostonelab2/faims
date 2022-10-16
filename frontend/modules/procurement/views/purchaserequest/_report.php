<div class="print-container">
<?php
    $fin="";
    $x=0;
    foreach ($prdetails as $pr) {
        $x++;
        if ($x==1) { $unit = $pr["name_short"]; } else { $unit = $pr["name_long"]; }
        $itemdescription = $pr["purchase_request_details_item_description"];
        $quantity = $pr["purchase_request_details_quantity"];
        $price = $pr["purchase_request_details_price"];

        if ($price=='0.00' || $price == null) {
            $price = "";
            $totalcost = "";
        }else{
            $totalcost =  $quantity * $price;
            $totalcost = number_format($totalcost,2) ;
       	    $price = number_format($price, 2);
        }
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='12%' style='padding-left: 60px;vertical-align: top;'>".$unit."</td>";
        $append = $append . "<td width='50%' style='text-align: justify;padding-left: 40px;vertical-align: top;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='10%' style='padding-left: 35px;vertical-align: top;'>" . $quantity . "</td>";
        $append = $append . "<td width='14%' style='padding-left: 30px;vertical-align: top;'>" . $price . "</td>";
        $append = $append . "<td width='14%' style='padding-left: 35px;vertical-align: top;'>" . $totalcost . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>
    </table>

</div>
