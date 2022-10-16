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
        $adddress=$pr["address"];
        $totalcost = $price;
        $dv_type = $pr["dv_type"];
        $assig1 =  $pr["Assig1"];
        $assig2 =  $pr["Assig2"];
        $assig3 =  $pr["Assig3"];
        $Assig1Position =  $pr["Assig1Position"];
        $Assig2Position =  $pr["Assig2Position"];
        $Assig3Position =  $pr["Assig3Position"];
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='75%' height='125' style='text-align: justify;vertical-align: top;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='25%' style='text-align: right;vertical-align: top;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    
    ?>
<table style="width: 100%; border-collapse: collapse;" border="1">
<tbody>
<tr style="height: 5px;">
<td style="width: 80%; height: 5px; text-align: center;border-bottom:none;" colspan="5"><br /><b><?= $assig->CompanyTitle; ?></p></td>
<td style="width: 20%; height: 5px;" colspan="2">Fund Cluster :<br /><br /></td>
</tr>
<tr style="height: 13px;">
<td style="width: 80%; height: 21px; text-align: center;font-size:16px;font-family:Arial;border-top:none;" colspan="5" rowspan="2"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DISBURSEMENT VOUCHER</strong></td>
<td style="width: 20%; height: 13px;" colspan="2">Date : <?= $dv_date; ?></td>
</tr>
<tr style="height: 8px;">
<td style="width: 80%; height: 8px;" colspan="2">DV No. :</td>
</tr>
<tr style="height: 13px;">
<td style="width: 10%; height: 25px;">Mode of <br />Payment</td>
<td style="width: 20%; height: 25px;text-align:center;border:none;border-bottom:1px solid black;">&nbsp;<span style="border-bottom:1px solid black;"></span>
<span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> MDS Check</td>
<td style="width: 20%; height: 25px;text-align:center;border:none;border-bottom:1px solid black;"><span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Commercial Clerk</td>
<td style="width: 20%; height: 25px;text-align:center;border:none;border-bottom:1px solid black;border-right:none;"><span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> ADA</td>
<td style="width: 30%; height: 25px;vertical-align:middle;border-left:none;padding-top:10px;padding-bottom:10px;" colspan="3"><span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Others (Please Specify)</td>
</tr>
<tr style="height: 13px;">
<td style="width: 10%; height: 4px;">
<p>Payee</p>
</td>
<td style="width: 50%; height: 25px;" colspan="3"> <?= $payee; ?></td>
<td style="width: 20%; height: 25px;">TIN/Employee No.:</td>
<td style="width: 20%; height: 25px;" colspan="2">ORS/BURS No.:</td>
</tr>
<tr style="height: 14px;">
<td style="width: 10%; height: 25px;">Address</td>
<td style="width: 90%; height: 25px;" colspan="6"><?= $adddress; ?></td>
</tr> 
<tr style="height: 14px;">
<td style="width: 50%; height: 14px; text-align: center;padding:10px;" colspan="3">Particulars</td>
<td style="width: 15%; height: 14px; text-align: center;padding:8px;padding-left:4px;">Responsibility&nbsp; Center</td>
<td style="width: 15%; height: 14px; text-align: center;padding:10px;">MFO/PAP</td>
<td style="width: 20%; height: 14px; text-align: center;padding:10px;" colspan="2">Amount</td>
</tr>
<tr style="height: 14px;">
<td style="width: 50%; height: 175px; text-align: left;padding:5px;vertical-align:top;" colspan="3"><?=  $itemdescription ?></td>
<td style="width: 15%; height: 175px; text-align: center;padding:5px;vertical-align:top;"></td>
<td style="width: 15%; height: 175px; text-align: center;padding:5px;vertical-align:top;"></td>
<td style="width: 20%; height: 175px; text-align: right;padding:5px;vertical-align:top;" colspan="2"><?= number_format($totalcost,2) ?></td>
</tr>
<tr style="height: 14px;">
<td style="width: 50%; height: 14px; text-align: center;" colspan="3">Amount Due&nbsp;&nbsp;&nbsp;</td>
<td style="width: 25%; height: 14px;" colspan="2">&nbsp;&nbsp;</td>
<td style="width: 25%; height: 14px;text-align:right;padding:5px;" colspan="2"><?=number_format($totalcost,2)?></td>
</tr>
<tr style="height: 14px;">
<td style="width: 100%; height: 0px; text-align: left;border-bottom:none;" colspan="7"><span style="vertical-align:top;"><span style="border:1px solid black;">A.</span> Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</span></td>
</tr>
<tr style="height: 14px;">
<td style="width: 100%; height: 0px; text-align: center;border-top:none;height:75px;" colspan="7"><span style="vertical-align:top;"> <?php if($assig1=='') { echo'<span style="text-decoration:underline;">'.$assig2.'<br></span>'.$Assig2Position.''; }else{echo'<span style="text-decoration:underline;font-weight:bold;">'.$assig1.'<br></span>'.$Assig1Position.'';} ?></span></td>
</tr>
<tr style="height: 14px;">
<td style="width: 100%; height: 0px; text-align: left;" colspan="7"><span style="vertical-align:top;"><span style="border:1px solid black;">B.</span> Accounting Entry</span></td>
</tr>
<tr style="height: 14px;">
<td style="width: 50%;  text-align: center;padding:5px;vertical-align:top;" colspan="3">Account Title</td>
<td style="width: 16.67%; text-align: center;padding:5px;vertical-align:top;">UACS Code</td>
<td style="width: 16.67%;  text-align: center;padding:5px;vertical-align:top;">Debit</td>
<td style="width: 16.67%;  text-align: center;padding:5px;vertical-align:top;" colspan="2">Credit</td>
</tr>
<tr style="height: 14px;">
<td style="width: 50%; height: 50px; text-align: left;padding:5px;vertical-align:top;" colspan="3"></td>
<td style="width: 16.67%; height: 50px; text-align: center;padding:5px;vertical-align:top;"></td>
<td style="width: 16.67%; height: 50px; text-align: center;padding:5px;vertical-align:top;"></td>
<td style="width: 16.67%; height: 50px; text-align: center;padding:5px;vertical-align:top;" colspan="2"></td>
</tr>
<tr style="height: 14px;">
<td style="width:50%; height: 0px; text-align: left;" colspan="3"><b><span style="border:1px solid black;">C.</span>Certified</b></td>
<td style="width:50%; height: 0px; text-align: left;" colspan="4"><b><span style="border:1px solid black;">D.</span>Approved for Payment</b></td>
    </tr>
    <tr style="height: 14px;">
    <td style="width:50%; height: 60px; text-align: left;padding:20px;" colspan="3"><span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Cash available <br><br><span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Subject to Authority to Debit Account (when applicable) <br><br> <span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Supporting documents complete and amount claimed proper.</td>
    <td style="width:50%; height: 60px; text-align: left;" colspan="4"><b><span style="border:1px solid black;"></span></b></td>
    </tr>
    </tbody>
    </table>
    <table style="width: 100%; border-collapse: collapse;" border="1">
    <tbody>
    <tr style="height: 12px;">
    <td style="width: 1%; height: 12px; text-align: center;padding:10px;">Signature</td>
    <td style="width: 49%; height: 12px;"></td>
    <td style="width: 5%; text-align: center; height: 12px;padding:10px;">Signature</td>
    <td style="width: 45%; height: 12px;"></td>
    </tr>
    <tr style="height: 25px;">
    <td style="width: 1%; height: 25px; text-align: center;padding:10px;">Printed<br />Name</td>
    <td style="width: 49%; height: 25px;text-align:center;font-size:14px;font-weight:bold;"><?= $assig2 ?></td>
                                                                                                                         >
    <td style="width: 10%; text-align: center; height: 25px;">Printed<br />Name</td>
    <td style="width: 40%; height: 12px;text-align:center;font-size:14px;font-weight:bold;"><?= $assig3; ?></td>
    </tr>
    <tr style="height: 16px;">
    <td style="width: 1%; height: 32px; text-align: center;padding:10px;">Position</td>
    <td style="width: 49%; height: 25px;font-size:13px;text-align:center;"><?= $Assig2Position ?></td>
    <td style="width: 10%; text-align: center; height: 32px;padding:10px;"><br />Position<br /><br /></td>
    <td style="width: 40%; height: 16px;font-size:13px;text-align:center;"><?= $Assig3Position ?> </td>
    </tr>

<tr style="height: 12.4546px;">
<td style="width: 10%; height: 25px; text-align: center; padding:10px;">Date</td>  
<td style="width: 40%; height: 25px;">&nbsp;</td>
<td style="width: 10%; text-align: center; height: 25px;padding:10px;">Date</td>
<td style="width: 40%; height: 12.4546px;">&nbsp;</td>
</tr>
</tbody>
</table>
<table style="width: 100%; border-collapse: collapse;" border="1">
    <tbody>
    <tr style="height: 14px;">
        <td style="width:80%; height: 0px; text-align: left;" colspan="4"><b><span style="border:1px solid black;">E.</span>Receipt of Payment</b></td>
        <td style="width:20%; height: 0px; text-align: left;" colspan="1" >JEV No.</td>
    </tr>
    <tr style="height: 14px;">
        <td style="width:9%;"> <center>Check/ADA No. :</center></td>
        <td style="width:22%;"></td>
        <td style="width:21%;vertical-align:top;padding-top:0px;">Date :</td>
        <td style="width:24%;vertical-align:top;padding-top:0px;border-bottom:none;">Bank Name & Account Number:<br> Land Bank of The Philippines</td>
        <td style="width:24%;border-top:none;"></td>
    </tr>
    <tr style="height: 14px;">
        <td style="width:9%;verticalign:middle;padding-top:8px;"> <center>Signature : </center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td style="width:22%;"></td>
        <td style="width:21%;vertical-align:top;padding-top:0px;">Date :</td>
        <td style="width:24%;vertical-align:top;padding-top:0px;">Printed Name : <br> <?= $payee; ?></td>
        <td style="width:24%;vertical-align:top;padding-top:0px;">Date</td>
    </tr>
    <tr>
        <td colspan="4">Official Receipt No. & Date/Other Documents</td>
        <td></td>
    </tr>
    </tbody>
</table>
</div>
