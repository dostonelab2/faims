<div class="print-container">
<div style="height: 145px;"></div>
<table style="width: 100%;border-collapse: collapse;" border="1">
<tbody>
<tr style="height: 26px;">
<td style="text-align: center; width: 0%; height: 26px;">Responsibility Center</td>
<td style="text-align: center; width: 43%; height: 26px;padding-left:0px;padding-right:0px;">Particulars</td>
<td style="text-align: center; width: 12%; height: 26px;">MFO/PAP</td>
<td style="text-align: center; width: 10%; height: 26px;">UACS Object<br /> Code</td>
<td style="text-align: center; width: 20%; height: 26px;">Amount</td>
</tr>
<tr style="height: 10.6px;">
<td style="width: 0%; height: 290px;vertical-align:top;border-bottom:none;">&nbsp;</td>
<td style="width: 43%; height: 290px;vertical-align:top;padding:3px;border-bottom:none;"><?= $model->particulars ?></td>
<td style="width: 12%; height: 290px;vertical-align:top;border-bottom:none;"><?= $model->ppa ?></td>
<td style="width: 10%; height: 290px;vertical-align:top;border-bottom:none;"><?= $model->account_code ?></td>
<td style="width: 20%; height: 290px;vertical-align:top;padding-left:15px;padding-top:25px;text-align:right;padding:5px;"><?= number_format($model->amount,2) ?></td>
</tr>
<tr style="height: 3px;">
<td style="width: 0%;border-top:none;">&nbsp;</td>
<td style="width: 43%;text-align:right;padding-right:50px;border-top:none;">Total</td>
<td style="width: 12%;border-top:none;"></td>
<td style="width: 10%;border-top:none;"></td>
<td style="width: 20%;border-top:none;padding-left:15px;text-align:right;padding:5px;"><?= number_format($model->amount,2) ?></td>
</tr>
</tbody>
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

 ?>
    <table style="border-collapse: collapse;width:100%;border:1px solid black;" >
    <tbody>
        <tr>
            <td style="border-right:1px solid black;width:50%;padding:5px;"><span style="border:1px solid black; padding:25px;">A. </span>&nbsp;&nbsp;Certified : Charges to appropriation/allotment necessary, lawful and under my direct 
            supervision; and supporting documents valid, proper and legal</td>
            <td style="width:50%;padding:5px;"><span style="border:1px solid black; padding:25px;">B. </span>&nbsp;&nbsp;Certified: Allotment available and obligated for the purpose/adjustment necessary as indicated above</td>
        </tr>
        <tr>
           <td style="height:15px;border-right:1px solid black;padding:5px;"></td>
           <td style="height:15x;"></td>
        </tr>
        <tr>
           <td style="border-right:1px solid black;width:50%;padding:5px;">Signature   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span>__________________________________</span></td>
           <td style="width:50%;padding:5px;">Signature   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span>__________________________________</span></td>
        </tr>
        <tr>
            <td style="border-right:1px solid black;width:50%;padding:5px;">Printed Name : <span style="text-decoration:underline;text-align:center;"><b><?=$requestedby;?></b></span></td>
            <td style="width:50%;padding:5px;">Printed Name :<span style="text-decoration:underline;text-align:center;"><b><?=$fundsavailable?></b></span><td>
        </tr>
        <tr>
            <td style="border-right:1px solid black;width:50%;padding:5px;">Position   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span style="text-decoration:underline;text-align:center;"><?=$requestedposition;?></span></td>
            <td style="width:50%;padding:5px;">Position   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span style="text-decoration:underline;"><?=$fundsposition;?></span></td>
        </tr>
        <tr>
           <td style="border-right:1px solid black;width:50%;padding:5px;">Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span>__________________________________</span></td>
           <td style="width:50%;padding:5px;">Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span>__________________________________</span></td>
        </tr>
        <tr>
            <td style="with:50%;background:black;"></td>
            <td style="with:50%;background:black;"></td>
        </tr>
        <tr>
            <td style="height:5px;border-right:1px solid black;height:5px;border-bottom:1px solid black;"></td>
            <td style="height:5px;border-bottom:1px solid black;"></td>
        </tr>
        </tbody>
   </table>
   <table style="border-collapse: collapse;width:100%;border:1px solid black;">
        <tr>
            <td style="height:5px;width:5%;border-right:1px solid black;padding:5px;padding-bottom:0px;"><span style="text-align:left;">C.</span></td>
            <td style="height:5px;width:95%;text-align:center;font-weight:bold;padding-bottom:0px;"><h5>STATUS OF OBLIGATION</h5></td>
        </tr>
   </table>
   <table style="border-collapse: collapse;width:100%;border:1px solid black;">
        <tr>
            <td style="height:5px;width:35%;border-right:1px solid black;padding:5px;padding-bottom:0px;text-align:center;"><span style="">Reference</span></td>
            <td style="height:5px;width:65%;text-align:center;padding-bottom:0px;"><h5>Amount</h5></td>
        </tr>
   </table>
   <table border="1" style="width: 100%; border-collapse: collapse;">
<tbody>
<tr style="height: 14px;">
<td style="width: 5%; height: 40px; text-align: center;" rowspan="3">Date<br /><br /></td>
<td style="width: 15%; height: 40px; text-align: center;" rowspan="3">Particulars<br /><br /></td>
<td style="width: 10%; height: 40px; text-align: center;padding:4px;" rowspan="3">ORS/JEV/CHECK/<br />ADA/TRA No.<br /><br /></td>
<td style="width: 15%; height: 27px; text-align: center;" rowspan="2"><br />Obligation<br /><br /></td>
<td style="width: 20%; height: 27px; text-align: center;" rowspan="2">Payable</td>
<td style="width: 20%; height: 27px; text-align: center;" rowspan="2">Payment</td>
<td style="text-align: center; width: 15%; height: 14px;" colspan="2">Balance</td>
</tr>
<tr style="height: 13px; text-align: center;">
<td style="width: 10%; text-align: center; height: 13px;">Not Yet Due</td>
<td style="width: 10%; height: 13px; text-align: center;">Due and Demandable</td>
</tr>
<tr style="height: 13px; text-align: center;">
<td style="width: 10%; text-align: center;height: 13px;">(a)</td>
<td style="width: 10%; text-align: center;height: 13px;">(b)</td>
<td style="width: 10%; text-align: center;height: 13px;">(c)</td>
<td style="width: 10%; text-align: center; height: 13px;">(a-b)</td>
<td style="width: 10%; height: 13px; text-align: center;">(b-c)</td>
</tr>
<tr style="height: 13px; text-align: center;">
<td style="width: 10%; height: 130px;">&nbsp;</td>
<td style="width: 10%; height: 130x;">&nbsp;</td>
<td style="width: 10%; height: 130px;">&nbsp;</td>
<td style="width: 10%; height: 130px;">&nbsp;</td>
<td style="width: 10%; height: 130px;">&nbsp;</td>
<td style="width: 10%; height: 130px;">&nbsp;</td>
<td style="width: 10%; text-align: center; height: 130px;">&nbsp;</td>
<td style="width: 10%; height: 130px; text-align: center;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>