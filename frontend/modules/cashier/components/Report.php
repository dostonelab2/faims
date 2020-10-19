<?php

namespace frontend\modules\cashier\components;

use Yii;
use kartik\mpdf\Pdf;
//use rmrevin\yii\fontawesome\FA;
use common\models\cashier\Lddapada;

class Report {

    function Lddapada($id)
    {
        \Yii::$app->view->registerJsFile("css/pdf.css");
        //$config= \Yii::$app->components['reports'];
        //$ReportNumber=(int)$config['ReportNumber'];
       
        $template = $this->template($id);
        
        /*if($ReportNumber==1){
             $mTemplate = $this->RequestTemplate($id);
        }elseif($ReportNumber==2){
            $mTemplate=$this->FastReport($id);
        }else{// in case does not matched any
            $mTemplate="<div class='col-md-12 danger'><h3>Report Configuration is not properly set.</h3></div>";
        }*/
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
        $mPDF->orientation = Pdf::ORIENT_PORTRAIT;
        $mPDF->defaultFontSize = 8;
        $mPDF->defaultFont = 'Arial';
        $mPDF->format =Pdf::FORMAT_A4;
        $mPDF->destination = Pdf::DEST_BROWSER;
        $mPDF->methods =['SetFooter'=>['|{PAGENO}|']];
       // $mPDF->SetDirectionality='rtl';
        $mPDF->render();
        exit;
    }
    
    
    function template($id)
    {
        $skip = 180;
        $skipRow = "<tr><td colspan='8'></td></tr>";
        
        $model = Lddapada::findOne($id);
        //$template = '<table border="0" style="border-collapse: collapse;font-size: 11px;table-layout:fixed" width="100%">';
        // REPORT HEADER
        $template = "<table style='border-collapse: collapse; font-size: 10px; cell-spacing: 0px; border: 1px solid #000;' width=100%>";
        $template .= "<tr>";
        $template .= "<th colspan='8'>LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE - ADVICE TO DEBIT ACCOUNTS (LDDAP-ADA)</th>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='1'>Department :</td>";
        $template .= "<td colspan='2'>Department of Science and Technology - IX</td>";
        $template .= "<td colspan='2'>&nbsp;</td>";
        $template .= "<td width='15%'>LDDAP-ADA No.</td>";
        $template .= "<td colspan='2'>".$model->batch_number."</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='1'>Entity Name :</td>";
        $template .= "<td colspan='4'>&nbsp;</td>";
        $template .= "<td>Date:</td>";
        $template .= "<td colspan='2'>08/06/2020</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='1'>Operating Unit :</td>";
        $template .= "<td colspan='4'>&nbsp;</td>";
        $template .= "<td>Fund Cluster :</td>";
        $template .= "<td colspan='2'>".Lddapada::FUND_CLUSTER."</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000;' colspan='8'>MDS-GSB BRANCH / MDS SUB ACCOUNT NO.: ".Lddapada::ACCOUNT."</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='border-bottom: 1px solid #000;' colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        // ITEMS HEADER
        $template .= "<tr>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' colspan='2'>CREDITOR</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' rowspan='2' width='12%'>Obligation<br/>Request and<br/>Status No.</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' rowspan='2' width='12%'>ALLOTMENT<br/>CLASS per<br/>(UACS)</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' colspan='3'>(IN PESOS)</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' rowspan='2'>REMARKS</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' width='18%'>NAME</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' width='18%'>PREFERRED<BR/>SERVICING BANK<BR/>SAVINGS/CURRENT<BR/>ACCOUNT NO.</td>";
        //$template .= "<td>ROA/ALOBS NO.</td>";
        //$template .= "<td>ALLOTMENT CLASS</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' width='10%'>GROSS<BR/>AMOUNT</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;' width='10%'>WITHHOLDING<BR/>TAX</td>";
        $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;'>NET<BR/>AMOUNT</td>";
        //$template .= "<td width='10%'>REMARKS</td>";
        $template .= "</tr>";
        
        // ITEMS
        $fmt = Yii::$app->formatter;
        foreach($model->lddapadaItems as $item){
            $template .= "<tr>";
            $template .= "<td style='border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$item->name."</td>";
            $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$item->creditor->account_number."</td>";
            $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$item->osdv_id."</td>";
            $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;'>000</td>";
            $template .= "<td style='text-align: right; padding-right: 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$fmt->asDecimal($item->osdv->getGrossamount())."</td>";
            $template .= "<td style='text-align: right; padding-right: 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$item->osdv->getTax()."</td>";
            $template .= "<td style='text-align: right; padding-right: 10px;border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$fmt->asDecimal($item->osdv->getNetamount)."</td>";
            $template .= "<td style='text-align: right; padding-right: 10px;border-bottom: 1px solid #000; border-right: 1px solid #000;'>000</td>";

            $template .= "</tr>";
            
            $skip -= 1;
        }
        
        $template .= "<tr>";
        $template .= "<td colspan='8'></td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        for($i=0; $i<=$skip; $i++){
            $template .= $skipRow;
        }
        
        // ITEMS FOOTER
        $template .= "<tr>";
        $template .= "<td style='border-top: 1px solid #000;' colspan='4'>TOTAL</td>";
        $template .= "<td style='text-align: right; padding-right: 10px; border-top: 1px solid #000;'>".$fmt->asDecimal($item->lddapada->total)."</td>";
        $template .= "<td style='border-top: 1px solid #000;'>-</td>";
        $template .= "<td style='border-top: 1px solid #000;'>12,000.00</td>";
        $template .= "<td style='border-top: 1px solid #000;'>-</td>";
        $template .= "</tr>";
        
        // PARAGRAPH
        $template .= "<tr>";
        $template .= "<td style='border-top: 1px solid #000;' colspan='3'>I hereby warrant the above List of Due and Demandable<br/>A/Ps was prepared in accordance with existing budgeting,<br/>accounting and auditing rules.</td>";
        $template .= "<td style='border-top: 1px solid #000;' colspan='5'>I hereby assume the fulle responsibility for the veracity and accuracy of the listed<br/>claims and the authenticity of supporting documents as submitted by the<br/>claimants.</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        //CERTIFIED / APPROVED
        $template .= "<tr>";
        $template .= "<td colspan='3'>CERTIFIED CORRECT:</td>";
        $template .= "<td colspan='5'>APPROVED</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td>_____________________________</td>";
        $template .= "<td>&nbsp;</td>";
        $template .= "<td>&nbsp;</td>";
        $template .= "<td colspan='2'>__________________________________</td>";
        $template .= "</tr>";
        
        //SIGNATORIES
        $template .= "<tr>";
        $template .= "<td colspan='3'>ROBERTO B. ABELLA</td>";
        $template .= "<td colspan='5'>MARTIN A. WEE</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='3'>Accountant III</td>";
        $template .= "<td colspan='5'>Regional Director</td>";
        $template .= "</tr>";
        
        
        // ADVICE TO DEBIT ACCOUNT (ADA)
        $template .= "<tr>";
        $template .= "<td style='border-top: 1px solid #000;' colspan='8'>ADVICE TO DEBIT ACCOUNT(ADA)</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>To: MDS GSB of the Agency</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>Please debit MDS Sub-Account Number</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>Please credit the accounts of the above listed creditors to cover payments of account payables (A/Ps)</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td>TOTAL AMOUNT:</td>";
        $template .= "<td colspan='7'>Twelve Thousand Only</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;_________________________________</td>";
        $template .= "<td>&nbsp;</td>";
        $template .= "<td colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;__________________________</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JALI B. BADIOLA</td>";
        $template .= "<td>&nbsp;</td>";
        $template .= "<td colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MARTIN A. WEE</td>";
        $template .= "</tr>";
        
        //MDS-GSB
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        $template .= "<tr style='border: 1px solid #000;'>";
        $template .= "<td style='text-align: center; border-top: 1px solid #000;' colspan='8'>(Erasures shall invalidate this document)</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FOR MDS-GSB USE ONLY:</td>";
        $template .= "</tr>";
        
        $space = 5;
        for($i=0;$i<$space;$i++){
            $template .= "<tr>";
            $template .= "<td colspan='8'>&nbsp;</td>";
            $template .= "</tr>";
        }
        
        //Instructions
        $template .= "<tr>";
        $template .= "<td style='border-top: 1px solid #000;' colspan='8'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Instructions:</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='padding-left: 20px;' colspan='8'>1. Agency shall arrange the creditors on a first-in, first-out basis, that is according to the date of receipt of supplier / creditors billing daily supported with complete documents.</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='padding-left: 20px;' colspan='8'>2. MDS-GSB branch concerned shall indicate under Remarks column, non-payments made to concerned creditors due to inconsistency in the information (creditor account name, number) between LDDAP-EC and bank records.</td>";
        $template .= "</tr>";
        
        $template .= "</table>";
        return $template;
    }
    
}