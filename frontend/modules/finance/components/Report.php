<?php

namespace frontend\modules\finance\components;

use Yii;
use kartik\mpdf\Pdf;
use common\models\finance\Request;
use common\models\finance\Osdv;
use common\models\procurement\Divisionhead;


class Report {
     
    function Obligationrequest($id)
    {
        \Yii::$app->view->registerJsFile("css/pdf.css");
       
        $template = $this->templateOS($id);
        
        $pdfFooter = [
            'L' => [
                'content' => '',
                'font-size' => 0,
                'font-style' => 'B',
                'color' => '#999999',
            ],
            'C' => [
                'content' => '{PAGENO}',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' => 'arial',
                'color' => '#333333',
            ],
            'R' => [
                'content' => '',
                'font-size' => 0,
                'font-style' => 'B',
                'font-family' => 'arial',
                'color' => '#333333',
            ],
            'line' => false,
        ];
        $mPDF = new Pdf(['cssFile' => 'css/pdf.css']);
        //$html = mb_convert_encoding($mTemplate, 'UTF-8', 'UTF-8');
        //$mPDF=$PDF->api;
        
        $mPDF->content = $template;
         
        /*$pdf->options = [
            'title' => 'Report Title',
            'subject'=> 'Report Subject',
            'defaultfooterline'=> 0];*/
        
        $mPDF->orientation = Pdf::ORIENT_PORTRAIT;
        $mPDF->defaultFontSize = 11;
        $mPDF->defaultFont = 'Arial';
        $mPDF->format =Pdf::FORMAT_A4;
        $mPDF->destination = Pdf::DEST_BROWSER;
        //$mPDF->methods =['SetFooter'=>['|{PAGENO}|']];
       // $mPDF->SetDirectionality='rtl';
        /*$mPDF->methods = [
            'SetHeader'=>[$template['header']],
            'SetFooter'=>[$template['footer']],
        ];*/
        $mPDF->render();
        exit;
    }
    
    function Disbursementvoucher($id)
    {
        \Yii::$app->view->registerJsFile("css/pdf.css");
       
        $template = $this->templateDV($id);
        
        $pdfFooter = [
            'L' => [
                'content' => '',
                'font-size' => 0,
                'font-style' => 'B',
                'color' => '#999999',
            ],
            'C' => [
                'content' => '{PAGENO}',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' => 'arial',
                'color' => '#333333',
            ],
            'R' => [
                'content' => '',
                'font-size' => 0,
                'font-style' => 'B',
                'font-family' => 'arial',
                'color' => '#333333',
            ],
            'line' => false,
        ];
        $mPDF = new Pdf(['cssFile' => 'css/pdf.css']);
        //$html = mb_convert_encoding($mTemplate, 'UTF-8', 'UTF-8');
        //$mPDF=$PDF->api;
        
        $mPDF->content = $template;
         
        /*$pdf->options = [
            'title' => 'Report Title',
            'subject'=> 'Report Subject',
            'defaultfooterline'=> 0];*/
        
        $mPDF->orientation = Pdf::ORIENT_PORTRAIT;
        $mPDF->defaultFontSize = 10;
        $mPDF->defaultFont = 'Arial';
        $mPDF->format =Pdf::FORMAT_A4;
        $mPDF->destination = Pdf::DEST_BROWSER;
        //$mPDF->methods =['SetFooter'=>['|{PAGENO}|']];
       // $mPDF->SetDirectionality='rtl';
        /*$mPDF->methods = [
            'SetHeader'=>[$template['header']],
            'SetFooter'=>[$template['footer']],
        ];*/
        $mPDF->render();
        exit;
    }
         
    function templateOS($id)
    {
        $model = Request::findOne($id);
        $fmt = Yii::$app->formatter;
        
        $boxAsignatory = Divisionhead::find(['division_id'=> $model->division_id])->one();
        
        // Header1
        $content = '<table width="100%"><tbody>
                        <tr style="height: 43.6667px;">
                            <td style="width: 82.4103%; height: 43.6667px;"><p>&nbsp;</p></td>
                            <td style="width: 12.5897%; height: 43.6667px;">
                                <table border="1" width="100%" style="border-collapse: collapse;">
                                    <tbody>
                                        <tr>
                                            <td><p><h6><strong>FASS-PUR F10</strong>&nbsp; Rev. 3/02-01-16</h6></p></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody></table>';
        
        // Header 2
        $content .= '<table style="width: 100%;border-collapse: collapse;" border="1">';
        $content .= '
        <tbody>
            <tr style="height: 16.6px;">
                <td style="width: 70%; text-align: center; height: 16.6px;border-bottom:none;">
                    <h2 style="font-size:16;font-family:Arial;font-weight:bold;">&nbsp;OBLIGATION REQUEST AND STATUS</h2>
                </td>
                <td style="width: 30%; height: 16.6px;">Serial No. : <b>'.$model->osdv->os->os_number.'</b></td>
            </tr>
            <tr style="height: 13px;">  
                <td style="width: 70.0192%; text-align: center; height: 13px;border-top:none;border-bottom:none;"><span style="text-decoration: underline;">
                <h1 style="font-size:16;font-family:Arial;font-weight:bold;">&nbsp;DEPARTMENT OF SCIENCE AND TECHNOLOGY - IX</h1>
                <strong></strong></span></td>
                <td style="width: 28.9808%; height: 13px;">Date : '.$model->osdv->os->os_date.' </td>
            </tr>
            <tr style="height: 13px;">
                <td style="width: 70.0192%; text-align: center; height: 13px;border-top:none;border-bottom:none;">Pettit Barracks, Zamboanga City</td>
                <td style="width: 28.9808%; height: 13px;">Fund Cluster :</td>
            </tr>
        </tbody></table>';
        
        $content .= '<table style="width: 100%;border-collapse: collapse;" border="1">
                        <tbody>
                        <tr style="height: 13px;">
                            <td style="width: 14%; text-align: center; height: 13px;">&nbsp;Payee</td>
                            <td style="width: 84%; height: 13px; font-weight: bold;">&nbsp;'.$model->creditor->name.'</td>
                        </tr>
                        <tr style="height: 13px;">
                            <td style="width: 14%; text-align: center; height: 13px;">&nbsp;Office</td>
                            <td style="width: 84%; height: 13px;">&nbsp;</td>
                        </tr>
                        <tr style="height: 13px;">
                            <td style="width: 14%; text-align: center; height: 13px;">&nbsp;Address</td>
                            <td style="width: 84%; height: 13px; font-weight: bold;">&nbsp;'.$model->creditor->address.'</td>
                        </tr>
                        </tbody>
                        </table>';
        
        $content .= '
                            <table style="width: 100%;border-collapse: collapse;" border="1">
                                <tbody>
                                <tr style="height: 36px;">
                                    <td style="text-align: center; width: 15.05%; height: 36px;">Responsibility<br/>Center</td>
                                    <td style="text-align: center; width: 44%; height: 36px;padding-left:0px;padding-right:0px;">Particulars</td>
                                    <td style="text-align: center; width: 10.05%; height: 36px;">MFO/PAP</td>
                                    <td style="text-align: center; width: 10%; height: 36px;">UACS<br/>Object<br/> Code</td>
                                    <td style="text-align: center; width: 10%; height: 36px;">Amount</td>
                                </tr>
                                <tr style="height: 10.6px;">
                                    <td style="width: 0%; height: 290px;vertical-align:top;border-bottom:none;">&nbsp;</td>
                                    <td style="width: 43%; height: 290px;vertical-align:top;padding:3px;border-bottom:none;">'.$model->particulars.'</td>
                                    <td style="font-size: 10px; width: 12%; height: 290px;vertical-align:top;padding-top:7px;text-align:center; border-bottom:none;">';
            
            foreach($model->osdv->allotments as $allotment){
                $content .= $allotment->name.'<br/>';
            }
            
            $content .= '</td>
                        <td style="font-size: 10px; width: 10%; height: 290px;vertical-align:top;padding-top:7px;text-align:center; border-bottom:none;">';
            
            foreach($model->osdv->allotments as $allotment){
                $content .= $allotment->expenditureobject->object_code.'<br/>';
            }
        
            $content .= '</td>
                                    <td style="width: 20%; height: 290px;vertical-align:top;padding-left:15px;padding-top:25px;text-align:right;padding:5px; font-weight: bold;">'.number_format($model->amount,2).'</td>
                                </tr>
                                <tr style="height: 3px;">
                                <td style="width: 0%;border-top:none;">&nbsp;</td>
                                <td style="width: 43%;text-align:right;padding-right:50px;border-top:none;">Total</td>
                                <td style="width: 12%;border-top:none;"></td>
                                <td style="width: 10%;border-top:none;"></td>
                                <td style="width: 20%;border-top:none;padding-left:15px;text-align:right;padding:5px; font-weight: bold;"><b>'.number_format($model->amount,2).'<b/></td>
                                </tr>
                                </tbody>
                            </table>';
        
            $fin="";
            $x=0;
            $loopss = "";
            /*foreach ($assig as $ob) {
                $requestedby = $ob["RequestedBy"];
                $requestedposition = $ob["RequestedPosition"];
                $fundsavailable = $ob["FundsAvailable"];
                $fundsposition = $ob["FundsAvailablePosition"];
            }*/
        
            $OSboxBSignatory = 'INGRID I. ABELLA-COLCOL';
            $OSboxBPosition = 'Budget Officer';
            switch ($model->division_id) {
              case 1:
                $OSboxASignatory = 'MARTIN A. WEE';
                $OSboxAPosition = 'Regional Director';
                break;
              case 2:
                $OSboxASignatory = 'ROSEMARIE S. SALAZAR';
                $OSboxAPosition = 'ARD-FASTS';
                break;
              case 3:
                $OSboxASignatory = 'ROSEMARIE S. SALAZAR';
                $OSboxAPosition = 'ARD-FASTS';             
                break;
              case 4:
                $OSboxASignatory = 'MAHMUD L. KINGKING';
                $OSboxAPosition = 'ARD-FOS';  
                break;
              default:
                $OSboxASignatory = '';
                $OSboxAPosition = ''; 
            }
       
        $content .= '<table style="border-collapse: collapse;width:100%;border:1px solid black;" >
                        <tbody>
                            <tr>
                                <td style="border-right:1px solid black;width:50%;padding:5px;"><span style="border:1px solid black; padding:25px;">A. </span>&nbsp;&nbsp;Certified : Charges to appropriation/allotment necessary, lawful and under my direct 
                                supervision; and supporting documents valid, proper and legal</td>
                                <td style="width:50%;padding-left:5px;padding-top:-16px;"><span style="border:1px solid black; padding:25px;">B. </span>&nbsp;&nbsp;Certified: Allotment available and obligated for the purpose/adjustment necessary as indicated above</td>
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
                                <td style="border-right:1px solid black;width:50%;padding:5px;">Printed Name : <span style="text-decoration:underline;text-align:center;"><b>'.$OSboxASignatory.'</b></span></td>
                                <td style="width:50%;padding:5px;">Printed Name :<span style="text-decoration:underline;text-align:center;"><b>'.$OSboxBSignatory.'</b></span><td>
                            </tr>
                            <tr>
                                <td style="border-right:1px solid black;width:50%;padding:5px;">Position   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span style="text-decoration:underline;text-align:center;">'.$OSboxAPosition.'</span></td>
                                <td style="width:50%;padding:5px;">Position   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span style="text-decoration:underline;">'.$OSboxBPosition.'</span></td>
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
                                <td style="height:5px;width:95%;text-align:center;font-weight:bold;padding-bottom:0px;"><h3>STATUS OF OBLIGATION</h3></td>
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
                        </table>';
       
        $content .= '
            <table>
                   <tr class="nospace-border">
                    <td width="50%" style="text-align: left;font-size: 11px;"></td>
                    <td width="50%" style="text-align: center;">'.'</td>
                </tr>
            </table>
            ';
        
        return $content;
    }
    
    function templateDV($id)
    {
        $model = Request::findOne($id);
        $fmt = Yii::$app->formatter;
        
        $DVboxCSignatory = 'ROBERTO B. ABELLA';
        $DVboxCPosition = 'Accountant III';
        $DVboxDSignatory = 'MARTIN A. WEE';
        $DVboxDPosition = 'Regional Director';
        switch ($model->division_id) {
          case 1:
            $DVboxASignatory = 'MARTIN A. WEE';
            $DVboxAPosition = 'Regional Director';
            break;
          case 2:
            $DVboxASignatory = 'ROSEMARIE S. SALAZAR';
            $DVboxAPosition = 'ARD-FASTS';
            break;
          case 3:
            $DVboxASignatory = 'ROSEMARIE S. SALAZAR';
            $DVboxAPosition = 'ARD-FASTS';             
            break;
          case 4:
            $DVboxASignatory = 'MAHMUD L. KINGKING';
            $DVboxAPosition = 'ARD-FOS';  
            break;
          default:
            $DVboxASignatory = '';
            $DVboxAPosition = ''; 
        }
        
        //$boxBsignatory = Divisionhead::find(['division_id'=> $model->division_id])->one();
        
        $content = '<table width="100%">
        <tbody>
        <tr style="height: 43.6667px;">
        <td style="width: 82.4103%; height: 43.6667px;">
        <p>&nbsp;</p>
        </td>
        <td style="width: 12.5897%; height: 43.6667px;">
        <table border="1" width="100%" style="border-collapse: collapse;">
        <tbody>
        <tr>
        <td>
        <p><h6 style-P><strong>FASS-PUR F13</strong><br/> Rev. 2/07-01-19</h6></p>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>';
        
        $assig1 = '';
        $assig2 = '';
        $Assig1 = '';
        $Assig2Position = '';
        
        $content .= '<table style="width: 100%; border-collapse: collapse;" border="1">
<tbody>
<tr style="height: 5px;">
<td style="width: 80%; height: 5px; text-align: center;border-bottom:none;" colspan="5"><br /><b>DEPARTMENT OF SCIENCE AND TECHNOLOGY IX</p></td>
<td style="width: 20%; height: 5px;" colspan="2">Fund Cluster :<br /><br /></td>
</tr>
<tr style="height: 13px;">
<td style="width: 80%; height: 21px; text-align: center;font-size:16px;font-family:Arial;border-top:none;" colspan="5" rowspan="2"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DISBURSEMENT VOUCHER</strong></td>
<td style="width: 20%; height: 13px;" colspan="2">Date : '.$model->osdv->dv->dv_date.'</td>
</tr>
<tr style="height: 8px;">
<td style="width: 80%; height: 8px;" colspan="2">DV No. : <b>'.$model->osdv->dv->dv_number.'</b></td>
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
<td style="width: 50%; height: 25px; padding-left: 5px; font-weight: bold;" colspan="3"> '.$model->creditor->name.'</td>
<td style="width: 20%; height: 25px;">TIN/Employee No.:</td>
<td style="width: 20%; height: 25px;" colspan="2">ORS/BURS No.:</td>
</tr>
<tr style="height: 14px;">
<td style="width: 10%; height: 25px;">Address</td>
<td style="width: 90%; height: 25px; padding-left: 5px; font-weight: bold;" colspan="6">'.$model->creditor->address.'</td>
</tr> 
<tr style="height: 14px;">
<td style="width: 40%; height: 14px; text-align: center;padding:10px;" colspan="3">Particulars</td>
<td style="width: 25%; height: 14px; text-align: center;padding:8px;padding-left:4px;">Responsibility&nbsp; Center</td>
<td style="width: 15%; height: 14px; text-align: center;padding:10px;">MFO/PAP</td>
<td style="width: 20%; height: 14px; text-align: center;padding:10px;" colspan="2">Amount</td>
</tr>
<tr style="height: 14px;">
<td style="width: 50%; height: 175px; text-align: left;padding:5px;vertical-align:top; padding-left: 5px; font-weight: bold;" colspan="3">'.$model->particulars.'</td>
<td style="width: 15%; height: 175px; text-align: center;padding:5px;vertical-align:top;"></td>
<td style="width: 15%; height: 175px; text-align: center;padding:5px;vertical-align:top; font-weight: bold;">';
        
        foreach($model->osdv->allotments as $allotment){
                $content .= $allotment->name.'<br/>';
            }
        $content .= '</td>

<td style="width: 20%; height: 175px; text-align: right;padding:5px;vertical-align:top; font-weight: bold;" colspan="2">'.number_format($model->amount,2).'</td>
</tr>
<tr style="height: 14px;">
<td style="width: 50%; height: 14px; text-align: center;" colspan="3">Amount Due&nbsp;&nbsp;&nbsp;</td>
<td style="width: 25%; height: 14px;" colspan="2">&nbsp;&nbsp;</td>
<td style="width: 25%; height: 14px;text-align:right;padding:5px; font-weight: bold;" colspan="2">'.number_format($model->amount,2).'</td>
</tr>
<tr style="height: 14px;">
<td style="width: 100%; height: 0px; text-align: left;border-bottom:none;" colspan="7"><span style="vertical-align:top;"><span style="border:1px solid black;">A.</span> Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</span></td>
</tr>
<tr style="height: 14px;">
<td style="width: 100%; height: 0px; text-align: center;border-top:none;height:75px;" colspan="7"><span style="vertical-align:top;"> 
<span style="text-decoration:underline;font-weight:bold;">'.$DVboxASignatory.'<br></span>'.$DVboxAPosition.'</td>
';
        
        /*$DVboxASignatory
        $DVboxPosition    
        switch ($model->division_id) {
          case 1:
            $content .= '<span style="text-decoration:underline;font-weight:bold;">MARTIN A. WEE<br></span>Regional Director';
            break;
          case 2:
            $content .= '<span style="text-decoration:underline;font-weight:bold;">ROSEMARIE S. SALAZAR<br></span>ARD-FASTS';
            break;
          case 3:
            $content .= '<span style="text-decoration:underline;font-weight:bold;">ROSEMARIE S. SALAZAR<br></span>ARD-FASTS';
            break;
          case 4:
            $content .= '<span style="text-decoration:underline;font-weight:bold;">MAHMUD L. KINGKING<br></span>ARD FOS';
            break;
          default:
            $content .= '<span style="text-decoration:underline;font-weight:bold;"><br></span>';
        }*/
        
        /*if($model=='') { 
            $content .= '<span style="text-decoration:underline;">'.$assig2.'<br></span>'.$Assig2Position.''; 
        }else{
            $content .= '<span style="text-decoration:underline;font-weight:bold;">'.$assig1.'<br></span>'.$Assig1Position.'';
        }*/
        
$content .= '
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
<td style="width: 50%; height: 50px; text-align: left;padding:5px;vertical-align:top; font-weight: bold;" colspan="3">';
        
        foreach($model->osdv->accounttransactions as $transaction){
                $content .= $transaction->account->title.'<br/>';
            }
        
        $content .= '</td>
<td style="width: 16.67%; height: 50px; text-align: center;padding:5px;vertical-align:top; font-weight: bold;">';
        
        foreach($model->osdv->accounttransactions as $transaction){
                $content .= $transaction->account->object_code.'<br/>';
            }
        
        $content .= '</td>
<td style="width: 16.67%; height: 50px; text-align: center;padding:5px;vertical-align:top; font-weight: bold;">';
        
        foreach($model->osdv->accounttransactions as $transaction){
                $content .= ($transaction->debitcreditflag == 1) ? number_format($transaction->amount,2).'<br/>' : '-'.'<br/>';
            }
        
        $content .= '</td>
<td style="width: 16.67%; height: 50px; text-align: center;padding:5px;vertical-align:top; font-weight: bold;" colspan="2">';
        
        foreach($model->osdv->accounttransactions as $transaction){
                $content .= ($transaction->debitcreditflag == 2) ? number_format($transaction->amount,2).'<br/>' : '-'.'<br/>';
            }
        
        $content .= '</td>
</tr>
<tr style="height: 10px;">
<td style="width:50%; height: 0px; text-align: left;" colspan="3"><b><span style="border:1px solid black;">C.</span> Certified</b></td>
<td style="width:50%; height: 0px; text-align: left;" colspan="4"><b><span style="border:1px solid black;">D.</span> Approved for Payment</b></td>
    </tr>
    <tr style="height: 14px;">
    <td style="width:50%; height: 60px; text-align: left;padding:20px;" colspan="3">
        <span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;'.(array_search(1,explode(',',$model->osdv->osdv_attributes)) ? '/' : '').'&nbsp;&nbsp;&nbsp;</span> Cash available <br><br>
        <span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;'.(array_search(2,explode(',',$model->osdv->osdv_attributes)) ? '/' : '').'&nbsp;&nbsp;&nbsp;</span> Subject to Authority to Debit Account (when applicable) <br><br> 
        <span style="border:1px solid black;">&nbsp;&nbsp;&nbsp;'.(array_search(3,explode(',',$model->osdv->osdv_attributes)) ? '/' : '').'&nbsp;&nbsp;&nbsp;</span> Supporting documents complete and amount claimed proper.</td>
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
    <td style="width: 49%; height: 25px;text-align:center;font-size:14px;font-weight:bold;">ROBERTO B. ABELLA</td>
                                                                                                                         >
    <td style="width: 10%; text-align: center; height: 25px;">Printed<br/>Name</td>
    <td style="width: 40%; height: 12px;text-align:center;font-size:14px;font-weight:bold;">MARTIN A. WEE</td>
    </tr>
    <tr style="height: 16px;">
    <td style="width: 1%; height: 32px; text-align: center;padding:10px;">Position</td>
    <td style="width: 49%; height: 25px;font-size:13px;text-align:center;">Accountant III</td>
    <td style="width: 10%; text-align: center; height: 32px;padding:10px;"><br/>Position<br /><br /></td>
    <td style="width: 40%; height: 16px;font-size:13px;text-align:center;">Regional Director</td>
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
        <td style="width:24%;vertical-align:top;padding-top:0px;">Printed Name : <br><b> '.$model->creditor->name.'</b></td>
        <td style="width:24%;vertical-align:top;padding-top:0px;">Date</td>
    </tr>
    <tr>
        <td colspan="4">Official Receipt No. & Date/Other Documents</td>
        <td></td>
    </tr>
    </tbody>
</table>';
        return $content;
    }
 }