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
<div style="height: 30px;"></div>
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
<div style="height:30px;"></div>
<table width="100%">
        <tr class="nospace-border">
            <td style="padding-left: 135px;font-size: 13px; " width="55%"><?= $model->particulars ?></td>
            <td width="20%" style="text-align: center;"><?= $model->ppa ?></td>
            <td width="15%" style="text-align: center;"><?= $model->account_code ?></td>
            <td width="20%" style="text-align: right;padding-right: 40px;"><?= $model->amount ?></td>
        </tr>
</table>



</div>