<div class="print-container">

<?php
    $fin="";
    $x=0;
    $s=0;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["particulars"];
        $price = $pr["dv_amount"];
        $payee = $pr["payee"];
        $dvno = $pr["dv_no"];
        $dv_date = $pr["dv_date"];
        $prno = $pr["taxable"];
        $dvamount = $pr["dv_amount"];
        $totalcost = $price;
        $dv_type = $pr["dv_type"];
        $assig1 =  $pr["Assig1"];
        $assig2 =  $pr["Assig2"];
        $assig3 =  $pr["Assig3"];
        $Assig1Position =  $pr["Assig1Position"];
        $Assig2Position =  $pr["Assig2Position"];
        $Assig3Position =  $pr["Assig3Position"];
        $xxx = '
        <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="padding-bottom: 20px;padding-left: 50px;">'.$payee.'</td>
            <td style="text-align: right;"></td>
        </tr>
        <tr style="text-align: right;">
            <td style="padding-left: 50px;">Zamboanga City</td>
            <td style="text-align: right;"></td>
        </tr>
        <tr style="text-align: right;">
            <td></td>
            <td style="text-align: right;"></td>
        </tr>
        <tr style="text-align: right;">
            <td></td>
            <td style="text-align: right;"></td>
        </tr>
        </table>
        <div style="height: 25px;"></div>';
        echo $xxx;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='75%' height='125' style='text-align: justify;vertical-align: top;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='25%' style='text-align: center;vertical-align: top;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>

    </table>


<?php if($dv_type=='MDS') { ?>
    <div style="height: 65px;"></div>
    <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="text-align: center;padding-left: -60px;"><b><?= $assig2; ?></b><br><?= $Assig2Position; ?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;padding-right: -80px;"><b><?= $assig3; ?></b><br><?= $Assig3Position; ?></td>
        </tr>
        <tr style="text-align: right;">
            <td style="text-align: left;padding-left: 50px;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;padding-right: 50px;"></td>
        </tr>
    </table>
    <div style="height: 10px;"></div>
    <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="text-align: center;"></td>
            <td style="text-align: center;padding-left: 60px;">LAND BANK OF THE PHILIPPINES<br><?= $payee; ?></td>
            <td style="text-align: center;"></td>
        </tr>
    </table>

    <div style="height: 14px;"></div>

    <table width="100%">
        <tr>
            <td width="50%" style="text-align: left;font-size: 11px;font-weight: lighter;"><?= $dvno; ?></td>
            <td width="50%" style="text-align: right;font-size: 11px;font-weight: lighter;"><?= date("F j, Y"); ?></td>
        </tr>s
    </table>

<?php }else{ ?>

    <div style="height: 65px;"></div>

<table border="0" width="100%">
    <tr style="text-align: left;">
        <td style="text-align: center;"><b><?= $assig1; ?><b/><br><?= $Assig1Position; ?></td>
        <td style="text-align: center;"><b><?= $assig2; ?></b><br><?= $Assig2Position; ?></td>
        <td style="text-align: center;"><b><?= $assig3; ?><b/><br><?= $Assig3Position; ?></td>
    </tr>
    <tr style="text-align: right;">
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
    </tr>
</table>
    <div style="height: 10px;"></div>
    <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="text-align: center;"></td>
            <td style="text-align: center;padding-left: 60px;">LAND BANK OF THE PHILIPPINES<br><?= $payee; ?></td>
            <td style="text-align: center;"></td>
        </tr>
        <tr style="text-align: right;">
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
        </tr>
    </table>

    <div style="height: 25px;"></div>
    <table width="100%">
        <tr>
            <td width="50%" style="text-align: left;font-size: 11px;font-weight: lighter;"><?= $dvno; ?></td>
            <td width="50%" style="text-align: right;font-size: 11px;font-weight: lighter;"><?= date("F j, Y"); ?></td>
        </tr>
    </table>

<?php } ?>

    <div style="height: 155px;"></div>

    <?php
    $fin="";
    $x=0;
    $s=0;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["particulars"];
        $price = $pr["dv_amount"];
        $payee = $pr["payee"];
        $dvno = $pr["dv_no"];
        $dv_date = $pr["dv_date"];
        $prno = $pr["taxable"];
        $dvamount = $pr["dv_amount"];
        $totalcost = $price;
        $dv_type = $pr["dv_type"];
        $assig1 =  $pr["Assig1"];
        $assig2 =  $pr["Assig2"];
        $assig3 =  $pr["Assig3"];
        $Assig1Position =  $pr["Assig1Position"];
        $Assig2Position =  $pr["Assig2Position"];
        $Assig3Position =  $pr["Assig3Position"];
        $xxx = '
        <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="padding-bottom: 25px;padding-left: 50px;">'.$payee.'</td>
            <td style="text-align: right;"></td>
        </tr>
        <tr style="text-align: right;">
            <td style="padding-left: 50px;">Zamboanga City</td>
            <td style="text-align: right;"></td>
        </tr>
        <tr style="text-align: right;">
            <td></td>
            <td style="text-align: right;"></td>
        </tr>
        <tr style="text-align: right;">
            <td></td>
            <td style="text-align: right;"></td>
        </tr>
        </table>
        <div style="height: 30px;"></div>';
        echo $xxx;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='75%' height='125' style='text-align: justify;vertical-align: top;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='25%' style='text-align: center;vertical-align: top;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>


    <table border="0" width="100%">
        <?php
        echo $fin;
        ?>

    </table>


    <?php if($dv_type=='MDS') { ?>
        <div style="height: 35px;"></div>
        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;padding-left: -60px;"><b><?= $assig2; ?></b><br><?= $Assig2Position; ?></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;padding-right: -80px;"><b><?= $assig3; ?></b><br><?= $Assig3Position; ?></td>
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: left;padding-left: 50px;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: right;padding-right: 50px;"></td>
            </tr>
        </table>
        <div style="height: 10px;"></div>
        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;"></td>
                <td style="text-align: center;padding-left: 50px;">LAND BANK OF THE PHILIPPINES<br><?= $payee; ?></td>
                <td style="text-align: center;"></td>
            </tr>
        </table>

        <div style="height: 14px;"></div>



    <?php }else{ ?>

        <div style="height:35px;"></div>

        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;"><b><?= $assig1; ?></b><br><?= $Assig1Position; ?></td>
                <td style="text-align: center;"><b><?= $assig2; ?></b><br><?= $Assig2Position; ?></td>
                <td style="text-align: center;"><b><?= $assig3; ?></b><br><?= $Assig3Position; ?></td>
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
            </tr>
        </table>
        <div style="height: 20px;"></div>
        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;"></td>
                <td style="text-align: center;padding-left: 60px;">LAND BANK OF THE PHILIPPINES<br><?= $payee; ?></td>
                <td style="text-align: center;"></td>
            </tr>
        </table>



    <?php } ?>

</div>
