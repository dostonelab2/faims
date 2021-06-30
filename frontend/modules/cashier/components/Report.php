<?php

namespace frontend\modules\cashier\components;

use Yii;
use kartik\mpdf\Pdf;
//use rmrevin\yii\fontawesome\FA;
use common\models\finance\Accounttransaction;
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
        $skip = 60;
        $skipRow = "<tr><td colspan='8'></td></tr>";
        
        $model = Lddapada::findOne($id);
        
        switch ($model->type_id) {
          case 1:
            $account = Lddapada::ACCOUNT_MDS;
            break;
          case 2:
            $account = '';
            break;
          case 3:
            $account = '';
            break;
          case 4:
            $account = Lddapada::ACCOUNT_MDS_TRUST;
            break;
          default:
            $account = '';
        }
        //$template = '<table border="0" style="border-collapse: collapse;font-size: 11px;table-layout:fixed" width="100%">';
        // REPORT HEADER
        $template = "<table style='border-collapse: collapse; font-size: 10px; cell-spacing: 0px; border: 1px solid #000;' width=100%>";
        $template .= "<tr>";
        $template .= "<th colspan='8'>LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE - ADVICE TO DEBIT ACCOUNTS (LDDAP-ADA)</th>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='1'>Department :</td>";
        $template .= "<td colspan='2'>Department of Science and Technology - IX</td>";
        $template .= "<td colspan='4'>&nbsp;</td>";
        //$template .= "<td width='15%'>LDDAP-ADA No.</td>";
        //$template .= "<td colspan='2'>".$model->batch_number."</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='1'>Entity Name :</td>";
        $template .= "<td colspan='5'>&nbsp;</td>";
        //$template .= "<td>Date:</td>";
        //$template .= "<td colspan='2'>".date('m-d-Y',strtotime($model->batch_date))."</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='1'>Operating Unit :</td>";
        $template .= "<td colspan='6'>&nbsp;</td>";
        //$template .= "<td>Fund Cluster :</td>";
        //$template .= "<td colspan='2'>".Lddapada::FUND_CLUSTER2."</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='text-align: left; border-bottom: 1px solid #000;' colspan='8'>MDS-GSB BRANCH / MDS SUB ACCOUNT NO.: ".$account."</td>";
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
        
        $template .= "<tr>";
        $template .= "<td colspan='8' style='border-bottom: 1px solid #000; border-right: 1px solid #000; font-weight: bold;'>I. Current Year A/Ps</td>";
        $template .= "</tr>";
        
        // ITEMS
        $fmt = Yii::$app->formatter;
        foreach($model->lddapadaItems as $item){
            if(isset($model->request_payroll_id)){
                return $model->requestpayroll->tax;
            }else{
                if($item->creditor_id == 245){
                    $tax = Accounttransaction::find()->where(['request_id' => $item->osdv_id, 'account_id' => 31, 'debitcreditflag' => 2])->orderBy(['account_transaction_id' => SORT_DESC])->one();

                    $taxAmount = $tax->amount;
                }
                else
                    $taxAmount = $item->osdv->getTax();
                    //$taxAmount = '1';
            }
            
            $template .= "<tr>";
            $template .= "<td style='border-bottom: 1px solid #000; border-right: 1px solid #000; padding-left:  10px;'>".$item->name."</td>";
            $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$item->creditor->account_number."</td>";
            $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000; font-size: x-small;'>".($item->osdv->os ? $item->osdv->os->os_number : $item->osdv->dv->dv_number)."</td>";
            $template .= "<td style='text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".($item->osdv->uacs ? $item->osdv->uacs->expenditureobject->object_code : '-')."</td>";
            $template .= "<td style='text-align: right; padding-right: 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".number_format($item->request_payroll_id ? ($item->requestpayroll->amount) : $item->osdv->getGrossamount(),2)."</td>";
            $template .= "<td style='text-align: right; padding-right: 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;'>".number_format(
                 ($item->creditor_id == 245) ?
                    Accounttransaction::find()->where(['request_id' => $item->osdv_id, 'account_id' => 31, 'debitcreditflag' => 2])->orderBy(['account_transaction_id' => SORT_DESC])->one()->amount : $taxAmount
                    //Accounttransaction::find()->where(['request_id' => $item->osdv_id, 'account_id' => 31, 'debitcreditflag' => 2])->orderBy(['account_transaction_id' => SORT_DESC])->one()->amount : $item->osdv->getTax()
 
                ,2)."</td>";
            $template .= "<td style='text-align: right; padding-right: 10px;border-bottom: 1px solid #000; border-right: 1px solid #000;'>".number_format($item->request_payroll_id ? ($item->requestpayroll->amount - $item->requestpayroll->tax) : $item->osdv->getNetamount(),2)."</td>";
            $template .= "<td style='text-align: right; padding-right: 10px;border-bottom: 1px solid #000; border-right: 1px solid #000;'>".$item->check_number."</td>";

            $template .= "</tr>";
            
            $skip -= 1;
        }
        $template .= $skipRow;
        $template .= $skipRow;
        
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
        $template .= "<td colspan='0'>&nbsp;</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        
        
        for($i=0; $i<=$skip; $i++){
            $template .= $skipRow;
        }
        
        $template .= "<tr>";
        $template .= "<td colspan='4' style='border-top:  1px solid #000; border-bottom:  1px solid #000; padding-left: 10px;'>Sub-total</td>";
        $template .= "<td style='border-top:  1px solid #000;border-bottom:  1px solid #000;'>&nbsp;</td>";
        $template .= "<td style='border-top:  1px solid #000;border-bottom:  1px solid #000;'>&nbsp;</td>";
        $template .= "<td style='border-top:  1px solid #000;border-bottom:  1px solid #000;'>&nbsp;</td>";
        $template .= "<td style='border-top:  1px solid #000;border-bottom:  1px solid #000;'>&nbsp;</td>";
        $template .= "</tr>";
        
        $template .= $skipRow;
        $template .= $skipRow;
        
        $template .= "<tr>";
        $template .= "<td colspan='8' style='border-bottom: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;  font-weight: bold;'>II. Past's Year A/Ps</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        $template .= "<tr>";
        $template .= "<td colspan='8'>&nbsp;</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td colspan='4' style='border-top:  1px solid #000; padding-left: 10px; border-bottom: 1px solid #000;'>Sub-total</td>";
        $template .= "<td style='border-top:  1px solid #000; border-bottom: 1px solid #000;'>&nbsp;</td>";
        $template .= "<td style='border-top:  1px solid #000; border-bottom: 1px solid #000;'>&nbsp;</td>";
        $template .= "<td style='border-top:  1px solid #000; border-bottom: 1px solid #000;'>&nbsp;</td>";
        $template .= "<td style='border-top:  1px solid #000; border-bottom: 1px solid #000;'>&nbsp;</td>";
        $template .= "</tr>";
        
        $template .= $skipRow;
        $template .= $skipRow;
        
        // ITEMS FOOTER
        $template .= "<tr>";
        $template .= "<td style='border-top: 1px solid #000; border-right: 1px solid #000; text-align: center;font-weight: bold;' colspan='4'>TOTAL</td>";
        $template .= "<td style='text-align: right; padding-right: 10px; border-top: 1px solid #000;  border-right: 1px solid #000; font-weight: bold;'>".number_format($item->lddapada->total,2)."</td>";
        $template .= "<td style='border-top: 1px solid #000; border-right: 1px solid #000; text-align: right; padding-right: 10px;font-weight: bold;'>".number_format($item->lddapada->taxtotal,2)."</td>";
        $template .= "<td style='border-top: 1px solid #000;  border-right: 1px solid #000; text-align: right; padding-right: 10px;font-weight: bold;'>".number_format($item->lddapada->nettotal,2)."</td>";
        $template .= "<td style='border-top: 1px solid #000; border-right: 1px solid #000; text-align: right; padding-right: 10px;'></td>";
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
        $template .= "<td colspan='7'>".Yii::$app->NumbersToWords->convert($item->lddapada->nettotal)." ONLY</td>";
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
        $template .= "<td colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JALI J. BADIOLA</td>";
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
        
        $template .= "<table width='100%'>";
        
        $template .= "<tr>";
        $template .= "<td colspan='8'>NOTES:</td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='padding-left: 10px; width: 70%;'>The LDDAP-ADA is an accountable form</td>";
        $template .= "<td style='text-align: right; width: 30%;'>LDDAP-ADA NO. <u>".$model->batch_number."</u></td>";
        $template .= "</tr>";
        
        $template .= "<tr>";
        $template .= "<td style='padding-left: 10px; width: 70%;'>* Indicate in description/Name and UACS code</td>";
        $template .= "<td style='text-align: right; width: 30%;'>Date Issued: <u>".date('m-d-Y',strtotime($model->batch_date))."</u></td>";
        $template .= "</tr>";
        
        $template .= "</table>";
        return $template;   
    }
    
}