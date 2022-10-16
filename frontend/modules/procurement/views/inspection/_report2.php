<div class="print-container">
<?php
    $fin="";
    $x=0;
    $tots=0;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["bids_item_description"];
        $quantity = $pr["bids_quantity"];
        $price = $pr["bids_price"];
        $totalcost =  $quantity * $price;
        $append = "<tr class=\"nospace-border\" style='height: 200px;'>";
        $append = $append . "<td width='12%' style='padding-left: 25px;vertical-align: top;'>" . $quantity . "</td>";
        $append = $append . "<td width='10%' style='padding-left: 5px;vertical-align: top;'>units</td>";
        $append = $append . "<td width='48%' style='text-align: justify;vertical-align: top' autosize='0'>" . $itemdescription . "</td>";
        $append = $append . "<td width='18%' style='padding-left: 50px;text-align: right;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
        $tots = $tots + $totalcost;
    }
    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>
    </table>
    </div>

</div>
