<div class="print-container">
<?php
    $fin="";
    $x=0;
    //echo $model->purchase_request_referrence_no;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["purchase_request_details_item_description"];
        $quantity = $pr["purchase_request_details_quantity"];
        //$price = $pr["purchase_request_details_price"];
        $price="";
        $totalcost = 5000;
        if ($quantity>1) {
            $unit = $pr["name_short"];
        } else {
            $unit = $pr["name_long"];
        }
        $append = "<tr>";
        $append = $append . "<td width='10%' style='text-align: center;border:none;vertical-align: top;font-size:12px;'>".$x."</td>";
        $append = $append . "<td style='padding-left: 5px;vertical-align: top;font-size:12px;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='15%' style='text-align: center; vertical-align: top;font-size:12px;'>" . $quantity . " " . $unit . "</td>";
        $append = $append . "<td width='15%' style='font-size:11px;text-align: center;'>" . $price . "</td>";
        //$append = $append . "<td>" . $totalcost . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>

    <div></div>

    <table style="width:100%;border-collapse:collapse;" border="0">
        <?php
            echo $fin;
        ?>
    </table>

</div>