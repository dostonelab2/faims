<div class="print-container">
<div style="height: 75px;"></div>
<?php
    $fin="";
    $x=0;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["bids_item_description"];
        $quantity = $pr["bids_quantity"];
        $price = $pr["bids_price"];
        $totalcost =  $quantity * $price;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='12%' style='padding-left: 25px;'>" . $quantity . "</td>";
        $append = $append . "<td width='10%' style='padding-left: 5px;'>units</td>";
        $append = $append . "<td width='54%' style='text-align: justify;   '>" . $itemdescription . "</td>";
        $append = $append . "<td width='12%' style=''>" . number_format($totalcost,2) . "</td>";
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
