<div class="print-container">
<div style="height: 90px;"></div>
<?php //$model = new \common\models\procurement\Obligationrequest() ?>
<table align="right">
        <tr class="nospace-border">
            <td></td>
        </tr>
        <tr class="nospace-border">
            <td><?= $model->os_date ?></td>
        </tr>
</table>
<div style="height: 35px;"></div>
<table>
        <tr class="nospace-border">
            <td style="padding-left: 125px;padding-bottom: 10px;"><?= $model->payee ?></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding-left: 110px;padding-bottom: 10px;"><?= $model->office ?></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding-left: 125px;padding-bottom: 10px;"><?= $model->address ?></td>
        </tr>
</table>
<div style="height:35px;"></div>
<table border="0" width="100%">
        <tr class="nospace-border">
            <td style="padding-left: 135px;font-size: 13px;vertical-align: top;" width="55%" height="235"><?= $model->particulars ?></td>
            <td width="20%" style="text-align: center;vertical-align: top;"><?= $model->ppa ?></td>
            <td width="15%" style="text-align: center;vertical-align: top;"><?= $model->account_code ?></td>
            <td width="20%" style="text-align: right;padding-right: 40px;vertical-align: top;"><?= number_format($model->amount,2) ?></td>
        </tr>
</table>

    <?php
    $fin="";
    $x=0;
    $loopss = "";
    foreach ($assig as $ob) {
        $requestedby = $ob["RequestedBy"];
        $requestedposition = $ob["RequestedPosition"];
        $fundsavailable = $ob["FundsAvailable"];
        $fundsposition = $ob["FundsAvailablePosition"];
    }
    while($x>20) {
        $x++;
        $loopss  = $loopss.'<tr class="nospace-border"> <td></td><td></td></tr>';
    }


   $gg='<div style="height:5px;"></div>
    <table border="0" width="100%">
    '.$loopss.'
        <tr class="nospace-border">
            <td></td>
            <td style="text-align: right;padding-right: 40px;">'.number_format($model->amount,2).'</td>
        </tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>
        <tr class="nospace-border"> <td></td><td></td></tr>



        <tr class="nospace-border">
            <td width="50%" style="text-align: center;"><b>'.$requestedby.'</b></td>
            <td width="50%" style="text-align: center;padding-left:30px;"><b>'.$fundsavailable.'</b><td>
        </tr>
        <tr class="nospace-border">
            <td width="50%" style="text-align: center;">'.$requestedposition.'</td>
            <td width="50%" style="text-align: center;padding-left: 30px;">'.$fundsposition.'</td>
        </tr>
        </table>';


    echo $gg;
    ?>




</div>