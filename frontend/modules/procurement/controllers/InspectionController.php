<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Purchaseorderdetails;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaserequestSearch;
use common\models\procurement\Purchaseorder;
use yii\data\ArrayDataProvider;
use kartik\mpdf\Pdf;
use Yii;
class InspectionController extends \yii\web\Controller
{

    /***
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PurchaserequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $this->getPOList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mydata' => $data,
        ]);
    }



    /**
     * Displays a single PurchaseRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            return $this->renderAjax('_view', [
                'model' => $model,
            ]);
        }
    }


    public  function actionPurchaseOrder() {

        Modal::begin([
            'header' => '<h2>Hello world</h2>',
            'toggleButton' => ['label' => 'click me'],
        ]);
        echo 'Say hello...';
        Modal::end();

    }


    function getPOList()
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_order`.`purchase_order_number`  ,
        `tbl_bids_details`.`bids_details_id`,
        `fnGetSupplierName`(`tbl_bids`.`supplier_id`) AS supplier_name,
        `tbl_bids_details`.`bids_item_description` , 
        `tbl_bids_details`.`bids_quantity` , 
        `fais`.`fnGetUnits`(`tbl_purchase_request_details`.`unit_id`) AS bids_unit ,
        `tbl_bids_details`.`bids_price`,
`tbl_purchase_order`.`purchase_order_id`,
`tbl_purchase_order_details`.`delivered`,
`tbl_purchase_order`.`purchase_order_date`,
`tbl_purchase_request`.`purchase_request_number`,
`tbl_purchase_request`.`purchase_request_date`,
`tbl_purchase_order_details`.`purchase_order_details_id`
        FROM `fais-procurement`.`tbl_purchase_order`
        INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
        ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
        INNER JOIN `fais-procurement`.`tbl_bids_details`
        ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
        INNER JOIN `fais-procurement`.`tbl_bids` 
        ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`
        INNER JOIN `tbl_purchase_request`
        ON `tbl_purchase_request`.`purchase_request_id` = `tbl_bids_details`.`purchase_request_id`
        LEFT JOIN tbl_purchase_request_details
        ON `tbl_purchase_request_details`.`purchase_request_details_id` = `fais-procurement`.`tbl_bids_details`.`purchase_request_details_id`
        ORDER BY purchase_order_number DESC";
        $pordetails = $con->createCommand($sql)->queryAll();

        $x = 0;
        foreach ($pordetails as $pr) {
            $x++;
            $data[] = ['purchase_order_number' => $pr["purchase_order_number"],
                'bids_details_id' => $pr["bids_details_id"],
                'bids_unit' => $pr["bids_unit"],
                'supplier_name' => $pr["supplier_name"],
                'bids_item_description' => $pr["bids_item_description"],
                'bids_quantity' => $pr["bids_quantity"],
                'bids_price' => $pr["bids_price"],
                'purchase_order_id' => $pr["purchase_order_id"],
                'delivered' => $pr["delivered"],
                'purchase_order_details_id' => $pr["purchase_order_details_id"]
            ];
        }
        if ($x == 0) {
            $data[] = ['purchase_order_number' => '',
                'bids_details_id' => '',
                'bids_unit' => '',
                'supplier_name'=> '',
                'bids_item_description' => '',
                'bids_quantity' => '',
                'bids_price' => '',
                'purchase_order_id' => '',
                'delivered'=>'',
                'purchase_order_details_id' => ''
            ];
        }

        $pordetails = $data; //$provider;

        return $pordetails;

    }


    function getprDetails($id)
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_order`.`purchase_order_number`  ,
        `tbl_bids_details`.`bids_details_id`,
        `fnGetSupplierName`(`tbl_bids`.`supplier_id`) AS supplier_name,
        `tbl_bids_details`.`bids_item_description` , 
        `tbl_bids_details`.`bids_quantity` , 
         `fais`.`fnGetUnits`(`tbl_purchase_request_details`.`unit_id`) AS bids_unit ,
        `tbl_bids_details`.`bids_price`,
`tbl_purchase_order`.`purchase_order_id`,
`tbl_purchase_order_details`.`delivered`,
`tbl_purchase_order`.`purchase_order_date`,
`tbl_purchase_request`.`purchase_request_number`,
`tbl_purchase_request`.`purchase_request_date`,
`tbl_purchase_order_details`.`purchase_order_details_id`
        FROM `fais-procurement`.`tbl_purchase_order`
        INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
        ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
        INNER JOIN `fais-procurement`.`tbl_bids_details`
        ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
        INNER JOIN `fais-procurement`.`tbl_bids` 
        ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`
        INNER JOIN `tbl_purchase_request`
        ON `tbl_purchase_request`.`purchase_request_id` = `tbl_bids_details`.`purchase_request_id`
        LEFT JOIN tbl_purchase_request_details
ON `tbl_purchase_request_details`.`purchase_request_details_id` = `fais-procurement`.`tbl_bids_details`.`purchase_request_details_id`
                 WHERE `tbl_purchase_order`.`purchase_order_number` = '".$id."' and `tbl_purchase_order_details`.`delivered`=1
                 ORDER BY purchase_order_number";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }


    public function actionCheckselected()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request->post();
        $id = $request["chkRow"];
        $bools = $request["chkStatus"];
        Purchaseorderdetails::updateAll(['delivered' => $bools], 'purchase_order_details_id = ' . $id);
        $checkStatus=$id;
        return $checkStatus;
    }

    public function actionReportpo($id) {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModelDetails($id);
        $prdetails = $this->getprDetails($id);
        $assig = $this->getassig();
        $content = $this->renderPartial('_report', ['prdetails'=> $prdetails,'model'=>$model]);
        $pdf = new Pdf();
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;} .underline{
            border-bottom: 1px solid black;
            width: 100%;
            display: block;
        }';
        $supplier='';
        $ponum='';
        $prno='';
        $pdate='';
        $prdate='';
        $tots=0;
        foreach ($prdetails as $pr) {
            $supplier = $pr["supplier_name"];
            $ponum = $pr["purchase_order_number"];
            $pdate = $pr["purchase_order_date"];
            $prno = $pr["purchase_request_number"];
            $prdate = $pr["purchase_request_date"];
            $quantity = $pr["bids_quantity"];
            $units = $pr["bids_unit"];
            $price = $pr["bids_price"];
            $totalcost =  $quantity * $price;
            $tots = $tots + $totalcost;
        }
        foreach ($assig as $sg) {
            $assig1 =  $sg["Assig1"];
            $assig2 =  $sg["Assig2"];
            $assig3 =  $sg["Assig3"];
            $assig4 =  $sg["Assig4"];
            $Assig1Position =  $sg["Assig1Position"];
            $Assig2Position =  $sg["Assig2Position"];
            $Assig3Position =  $sg["Assig3Position"];
            $Assig4Position =  $sg["Assig4Position"];
        }
        $pdf->marginTop = 80;
        $pdf->marginBottom = 125;
        $pdf->marginFooter = 0;
        $pdf->marginHeader = 5;
$ss='<table width="100%">
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
<p><h6 style-P><strong>FASS-PUR F09</strong>&nbsp; Rev. 0 / 08-16-07</h6></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>

<table width="100%" style="border-collapse: collapse;" border="1">
<tbody>
<tr>
<td style="text-align: center;border-bottom:none;">Republic of the Philippines</td>
</tr>
<tr>        
<td style="text-align: center;border-bottom:none;border-top:none;"><strong>DEPARTMENT OF SCIENCE AND TECHNOLOGY</strong></td>
</tr>
<tr>
<td style="text-align: center;border-bottom:none;border-top:none;">Regional Office No. IX</td>
</tr>
<tr>
<td style="text-align: center;border-bottom:none;border-top:none;">Pettit Barracks, Zamboanga City</td></tr>
<tr>
<td style="text-align: center;font-family:Arial;font-size:15px;border-top:none;padding:20px;"><b>INSPECTION & ACCEPTANCE REPORT</b></td>
</tr>
</tbody>                                                                                                                                                                                                                                                                                                                                                             
</table>
<table style="width: 100%; font-size: 11px; border:1px solid;">
<tbody>
<tr>
<td style="width: 11.4598%;">Supplier :</td>
<td style="width: 67.8773%;font-size: 11px; height: 20px;" colspan="5"  class="underline">'.$supplier.'</td>
<td style="width: 11.1777%; height: 20px; font-size: 11px;">IAR No. : </td>
<td style="width: 9.48521%;" class="underline">'.str_replace('PO','IAR',$ponum).'</td>
</tr>

<tr style="height: 20px;">
<td style="width: 10.8075%; height: 20px; font-size: 11px;">P.O. No. :</td>
<td style="width: 19.2701%; height: 20px; font-size: 11px;" class="underline">'.$ponum.'</td>
<td style="width: 7.14033%; height: 20px; font-size: 11px;">Date :</td>
<td style="width: 16.8723%; height: 20px; font-size: 11px;" class="underline"></td>
<td style="width: 10.5254%; height: 20px; font-size: 11px;">Invoice No. :</td>
<td style="width: 14.4746%; height: 20px; font-size: 11px;" class="underline"></td>
<td style="width: 5.44782%; height: 20px; font-size: 11px;">Date :</td>
<td style="width: 15.4619%; height: 20px; font-size: 11px;" class="underline"></td>
</tr>


<tr>
<td colspan="8" style="width: 100%; height: 20px; font-size: 11px;">Requisitioning Office/Department : _____________________________________________________________________________________________________________</td>
</tr>


</tbody>
</table>

';



$ss=$ss
.'
<table border="1" width="100%" style="border-collapse: collapse;">
<tr style="height: 200px">
<td width="12%" style="padding-left: 25px;vertical-align: top;font-size:11px;text-align:center;">Quantity</td>
<td width="10%" style="padding-left: 5px;vertical-align: top;font-size:11px;text-align:center;">Units</td>
<td width="48%" style="text-align: justify;vertical-align: top;font-size:11px;text-align:center;" autosize="0">Description</td>
<td width="18%" style="padding-left: 50px;text-align: right;font-size:11px;text-align:center;"></td>
</tr>
<tr >
<td width="12%" style="padding-left: 25px;vertical-align: top;font-size:11px;height:500px;"></td>
<td width="10%" style="padding-left: 5px;vertical-align: top;font-size:11px;height:500px;"></td>
<td width="48%" style="text-align: justify;vertical-align: top;font-size:11px;height:500px;" autosize="0"></td>
<td width="18%" style="padding-left: 50px;text-align: right;font-size:11px;height:500px;"></td>
</tr>
</table>
<table border="1" style="border-collapse: collapse; width: 100%;">
<tbody>
<tr style="height: 20px;">
<td style="width: 50%; text-align: center; height: 20px; border-bottom: none;font-weight:bold;">INSPECTION</td>
<td style="width: 50%; text-align: center; height: 20px; border-bottom: none;font-weight:bold;">ACCEPTANCE</td>
</tr>
<tr style="height: 20px;">
<td style="width: 50%; text-align: left; height: 20px; border-top: none; border-bottom: none;font-size:11px;">&nbsp;Date Inspected : ________________</td>
<td style="width: 50%; height: 20px; text-align: left; border-top: none; border-bottom: none;font-size:11px;">&nbsp;Date Received : ________________</td>
</tr>
<tr style="height: 20px;">
<td style="width: 50%; text-align: left; height: 250px; vertical-align: top; border-top: none;"><span style="font-size: 55px; vertical-align: top;">▯ <span style="font-size: 11px; vertical-align: top;">Inspected, verified and found OK as to quantity & specifications.</span></span>
<p style="text-align: left; padding-left: 15px;"></p>
</td>
<td style="width: 50%; text-align: left; height: 250px; vertical-align: top; border-top: none;">
<p style="text-align: center; padding-left: 150px;"><span style="font-size: 50px;">□ <span style="font-size: 11px; vertical-align: middle;">Full</span></span></p>
<p style="text-align: left; padding-left: 150px; margin-top: -50px;"><span style="font-size: 50px;">□ <span style="font-size: 11px; vertical-align: middle;">Partial</span></span></p>
</td>
</tr>
</tbody>
</table>
';


        $headers= '<table border="0" width="100%">q
                        <tr style="text-align: left;">
                            <td style="padding-left: 50px;">'.$supplier.'</td>
                            <td style="text-align: right;">'.str_replace('PO','IAR',$ponum).'</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td style="padding-left: 50px;">'.$ponum.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$pdate.'</td>
                            <td style="text-align: right;padding-left: 35px;">'.$pdate.'</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>                                       
                    </table>    
                    ';
        $fin=0;
        foreach ($prdetails as $pr) {
            $quantity = $pr["bids_quantity"];
            $price = $pr["bids_price"];
            $totalcost = $quantity * $price;
            $fin = $fin + $totalcost;
        }
        $footerss= '
                    <div style="height: 0px"></div>
                    <table border="0" width="100%">
                        <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"><span style="text-decoration:underline">' . number_format($fin,2) . '</span></td>
                               <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                            </tr>
                            <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                            </tr>
                            <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                            </tr>
                            <tr style="text-align: left;">
                            <td></td>   
                            <td style="text-align: right;"></td>
                            </tr>
                            <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                            </tr>
                            <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                            </tr>
                            <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                    <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                        <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                        <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>                <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>                <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                 <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                 <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                                 <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                           <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                           <tr style="text-align: left;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr>
                        </tr>
                        
                        <tr style="text-align: left;">
                            <td style="text-align: center;padding-left: -125px;padding-bottom:10px;font-size:12px;"><b>'.$assig1.'</b><br>Chairman<br></td>
                            <td style="text-align: right;"></td>
                        </tr>
                 
                        <tr style="text-align: left;">
                            <td style="text-align: center;padding-left: -125px;padding-bottom:10px;font-size:12px;"><b>'.$assig2.'</b><br>Member<br></td>
                            <td style="text-align: right;text-align: center;font-size:12px;"><b>'.$assig3.'</b><br>Supply Officer</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td style="text-align: center;padding-left: -125px;font-size:12px;"><b>'.$assig4.'</b><br>Member</td>
                            <td style="text-align: right;"></td>
                        </tr>  
                        <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>      
                        <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                                <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                          <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>                       
                        <tr style="text-align: right;">
                            <td style="padding-left: 40px;"></td>
                            <td style="text-align: right;"></td>
                        </tr>  
                        <tr style="text-align: right;">
                            <td>'.date("F j, Y").'</td>
                            <td style="text-align: right;">Page {PAGENO} of {nbpg}</td>
                        </tr>              
                    </table>
                    ';
        $LeftFooterContent = '<div style="text-align: left;">'.date("F j, Y").'</div>';
        $CenterFooterContent = '';
        $RightFooterContent = '<div style="text-align: right;">Page {PAGENO} of {nbpg}</div>';
        $oddEvenConfiguration =
            [
                'L' => [ // L for Left part of the header
                    'content' => $LeftFooterContent,
                    'font-size' => 7,
                    'footer-style-left' => 200,
                    'font-family' => 'Arial',
                    'color'=>'#000000'
                ],
                'C' => [ // C for Center part of the header
                    'content' => $CenterFooterContent,
                    'font-size' => 6,
                    'font-style' => 'B',
                    'font-family' => 'arial',
                    'color'=>'#000000',
                ],
                'R' => [
                    'content' => $RightFooterContent,
                    'font-size' => 6,
                    'font-style' => 'B',
                    'font-family' => 'arial',
                    'color'=>'#000000'
                ],
                'line' =>0, // That's the relevant parameter
            ];
        $headerFooterConfiguration = [
            'odd' => $oddEvenConfiguration,
            'even' => $oddEvenConfiguration
        ];
        $pdf->options = [
            'title' => 'Report Title',
            'defaultheaderline' => 0,
            'defaultfooterline' => 0,
            'subject'=> 'Report Subject'];
        $pdf->methods = [
            'SetHeader'=>[$ss],
            'SetFooter'=>[$footerss],
        ];

        return $pdf->render();
    }


    function getassig()
    {
        $con = Yii::$app->db;
        $sql = "SELECT `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_1`) AS Assig1 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_1`) AS Assig1Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_2`) AS Assig2 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_2`) AS Assig2Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_3`) AS Assig3 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_3`) AS Assig3Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_4`) AS Assig4 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_4`) AS Assig4Position
	       	FROM `tbl_assignatory`
	WHERE `tbl_assignatory`.`assignatory_id` = 6";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }


    /**
     * Finds the PurchaseRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchaseRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchaserequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelDetails($id)
    {
        if (($model = Purchaseorder::findOne($id)) !== null) {
            return $model;
        } else {
            //return var_dump($model);
            //throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
