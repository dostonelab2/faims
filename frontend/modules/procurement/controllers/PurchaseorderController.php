<?php

namespace frontend\modules\procurement\controllers;

use common\models\procurement\Purchaseorder;
use common\models\procurement\Purchaserequest;
use common\models\procurement\Bidsdetails;
use common\models\procurement\PurchaserequestSearch;
use yii\data\ArrayDataProvider;
use kartik\mpdf\Pdf;

//use yii\web\NotFoundHttpException;
use Yii;
//$model = new Purchaseorder();

class PurchaseorderController extends \yii\web\Controller
{

    /***
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PurchaserequestSearch();
        $model = new Purchaseorder();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $this->getPOList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
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
        if ($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            return $this->renderAjax('_view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays a single PurchaseRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewpo()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $id = $request->get('id');
        $myid = $request->get('mid');
        $session->set('myID', $id);
        $session->set('myID2', $myid);
        $model = $this->findModelBidDetails($id);
        $model2 = $this->findModelPurchase($myid);
        return $this->renderAjax('_form', [
            'model' => $model,
            'model2' => $model2,
        ]);
    }



    public function actionUpdate()
    {
        $model = new Bidsdetails();
        // $model2 = new Purchaseorder();
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $id = $session->get('myID');
        $myid = $session->get('myID2');
        $model = $this->findModelBidDetails($id);
        $model2 = $this->findModelBidDetails($myid);
        $delivery = $request->post('txtdelivery');
        $delivery_date = $request->post('txtdelivery_date');
        $delivery_term = $request->post('txtdelivery_term');
        $payment_term = $request->post('txtpayment_term');
        $mod_of_proc = $request->post('txtmode_of_procurement');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Purchaseorder::updateAll(
                [
                    'place_of_delivery' => $delivery,
                    'date_of_delivery' => $delivery_date,
                    'delivery_term' => $delivery_term,
                    'payment_term' => $payment_term,
                    'mode_of_procurement' => $mod_of_proc,
                ],
                'purchase_order_id = ' . $myid
            );

            return $this->redirect('index');
        }
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
        `tbl_purchase_order`.`purchase_order_status`,
        `tbl_purchase_request`.`purchase_request_number`,
        `tbl_purchase_request`.`purchase_request_date`
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
         WHERE `tbl_purchase_order_details`.`purchase_request_details_status`=1
         ORDER BY `tbl_purchase_order`.`purchase_order_number` DESC";
        $pordetails = $con->createCommand($sql)->queryAll();

        $x = 0;
        foreach ($pordetails as $pr) {
            $x++;
            $data[] = [
                'purchase_order_number' => $pr["purchase_order_number"],
                'bids_details_id' => $pr["bids_details_id"],
                'bids_unit' => $pr["bids_unit"],
                'supplier_name' => $pr["supplier_name"],
                'bids_item_description' => $pr["bids_item_description"],
                'bids_quantity' => $pr["bids_quantity"],
                'bids_price' => $pr["bids_price"],
                'purchase_order_id' => $pr["purchase_order_id"],
                'purchase_order_status' => $pr["purchase_order_status"]
            ];
        }
        if ($x == 0) {
            $data[] = [
                'purchase_order_number' => '',
                'bids_details_id' => '',
                'bids_unit' => '',
                'supplier_name' => '',
                'bids_item_description' => '',
                'bids_quantity' => '',
                'bids_price' => '',
                'purchase_order_id' => '',
                'purchase_order_status' => ''
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
         `fnGetSupplierAddress`(`tbl_bids`.`supplier_id`) AS supplier_address,
         `fnGetSupplierCode`(`tbl_bids`.`supplier_id`) AS supplier_code,
         `tbl_bids_details`.`bids_item_description` , 
         `tbl_bids_details`.`bids_quantity` , 
         `fais`.`fnGetUnits`(`tbl_purchase_request_details`.`unit_id`) AS bids_unit ,
         `tbl_bids_details`.`bids_price`,
        `tbl_purchase_order`.`purchase_order_id`,
        `tbl_purchase_order_details`.`delivered`,
        `tbl_purchase_order`.`purchase_order_date`,
        `tbl_purchase_request`.`purchase_request_number`,
        `tbl_purchase_request`.`purchase_request_date`
         FROM `fais-procurement`.`tbl_purchase_order`
         INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
         ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
         INNER JOIN `fais-procurement`.`tbl_bids_details`
         ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
         INNER JOIN `fais-procurement`.`tbl_bids` 
         ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`
         INNER JOIN `tbl_purchase_request`
         ON `tbl_purchase_request`.`purchase_request_id` = `tbl_bids_details`.`purchase_request_id`
         INNER JOIN tbl_purchase_request_details
         ON `tbl_purchase_request_details`.`purchase_request_details_id` = `fais-procurement`.`tbl_bids_details`.`purchase_request_details_id`
                 WHERE `tbl_purchase_order`.`purchase_order_number` = '" . $id . "' AND `tbl_purchase_order_details`.`purchase_request_details_status`=1";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }


    public function actionReportpo($id)
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModelDetails($id);
        $prdetails = $this->getprDetails($id);
        $assig = $this->getassig();
        $content = $this->renderPartial('_report', ['prdetails' => $prdetails, 'model' => $model]);
        $pdf = new Pdf();
        $pdf->mode = pdf::MODE_UTF8;
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;}h6 {  }';
        $supplier = '';
        $ponum = '';
        $prno = '';
        $pdate = '';
        $prdate = '';
        $supplier_address = '';
        foreach ($prdetails as $pr) {
            $supplier = $pr["supplier_name"];
            $supplier_address = $pr["supplier_address"];
            $supplier_code = $pr["supplier_code"];
            $ponum = $pr["purchase_order_number"];
            $pdate = $pr["purchase_order_date"];
            $prno = $pr["purchase_request_number"];
            $prdate = $pr["purchase_request_date"];
        }

        foreach ($assig as $sg) {
            $assig1 =  $sg["Assig1"];
            $assig2 =  $sg["Assig2"];
            $Assig1Position =  $sg["Assig1Position"];
            $Assig2Position =  $sg["Assig2Position"];
        }
        $pdf->marginTop = 60;
        //$pdf->marginHeader = 40;
        $pdf->marginBottom = 50;

        $headers = '<div style="height: 150px"></div>
                    <table border="0" width="100%">
                   
                    
                        <tr style="text-align: left;">
                            <td style="padding-left: 50px;">' . $supplier . '</td>
                            <td style="text-align: right;">' . $ponum . '</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td style="padding-left: 50px;">Zamboanga City</td>
                            <td style="text-align: right;">' . $pdate . '</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td></td>
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
                        <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;">' . $prno . '</td>
                        </tr>    
                        <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;">' . $prdate . '</td>
                        </tr>        
                        <tr style="text-align: right;">
                            <td style="padding-left: 85px;">Department of Science and Technology</td>
                            <td style="text-align: right;"></td>
                        </tr>                                          
                    </table>
                    ';
        $summary = 0;
        $totalcost = 0;
        foreach ($prdetails as $pr) {
            $quantity = $pr["bids_quantity"];
            $price = $pr["bids_price"];
            $totalcost =  $quantity * $price;
            $summary = $summary + $totalcost;
        }
        $footerss = '<div style="height: 10px"></div>

                    <table border="0" width="100%">
 
                     <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>
                      <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>
                      <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>      
                             <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>      
                        <tr style="text-align: left;">
                            <td style="padding-left: 80px;">' . $supplier . '</td>
                            <td style="text-align: center;">' . $assig2 . '<br>' . $Assig2Position . '</td>
                       </tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       
                        <tr style="text-align: right;padding-left: 50px;">
                            <td style="text-align: center;">' . $assig1 . '<br>' . $Assig1Position . '</td>
                            <td style="text-align: right;"></td>
                        </tr>  
                                 
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                    
                        <tr style="text-align: right;">
                            <td>' . date("F j, Y") . '</td>
                            <td style="text-align: right;">Page {PAGENO} of {nbpg}</td>
                        </tr>              
                    </table>
                    ';
        $pdf->options = [
            'title' => 'Report Title',
            'defaultheaderline' => 0,
            'defaultfooterline' => 0,
            'shrink_tables_to_fit' => 0,
            'subject' => 'Report Subject'
        ];

        $pdf->methods = [
            'SetHeader' => [$headers],
            'SetFooter' => [$footerss],
        ];

        return $pdf->render();
    }




    public function actionReportpofull($id)
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $mid = $request->get('mid');
        $model = $this->findModelDetails($mid);
        $prdetails = $this->getprDetails($id);
        $assig = $this->getassig();
        $content = $this->renderPartial('_report2', ['prdetails' => $prdetails, 'model' => $model]);
        $pdf = new Pdf();
        $pdf->mode = pdf::MODE_UTF8;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.page-break{ auto; }.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;}';
        $pdf->marginFooter = 5;

        $supplier = '';
        $ponum = '';
        $prno = '';
        $pdate = '';
        $prdate = '';
        $summary = 0;
        $totalcost = 0;
        foreach ($prdetails as $pr) {
            $supplier = $pr["supplier_name"];
            $supplier_address = $pr["supplier_address"];
            $supplier_code = $pr["supplier_code"];
            $ponum = $pr["purchase_order_number"];
            $pdate = $pr["purchase_order_date"];
            $prno = $pr["purchase_request_number"];
            $prdate = $pr["purchase_request_date"];
            $quantity = $pr["bids_quantity"];
            $price = $pr["bids_price"];
            $units = $pr["bids_unit"];
            $totalcost = $quantity * $price;
            $summary = $summary + $totalcost;
        }

        foreach ($assig as $sg) {
            $assig1 =  $sg["Assig1"];
            $assig2 =  $sg["Assig2"];
            $Assig1Position =  $sg["Assig1Position"];
            $Assig2Position =  $sg["Assig2Position"];
        }

        $pdf->marginTop = 82;
        //$pdf->marginHeader = 40;
        $pdf->marginBottom = 110;

$headers = '<table width="100%">
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
            <p><h6 style-P><strong>FASS-PUR F08</strong>&nbsp; Rev. 2/07-01-23</h6></p>
            </td>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            </tbody>
            </table>

            <table width="100%" style="border-collapse: collapse;" border="0">
            <tbody>
            <tr>
            <td style="text-align: center;font-family:Arial;font-size:15px;border-top:none;"><b>PURCHASE ORDER</b></td>
            </tr>
            <tr>
            <td style="text-align: center;font-family:Arial;font-size:15px;border-top:none;"><br></td>
            </tr>
            <tr>        
            <td style="text-align: center;border-bottom:none;border-top:none;"><strong><u>DEPARTMENT OF SCIENCE AND TECHNOLOGY</u></strong></td>
            </tr>
            <tr>
            <td style="text-align: center;border-bottom:none;border-top:none;">Entity Name</td>
            </tr>
            </tbody>                                                                                                                                                                                                                                                                                                                                                             
            </table>
            <table style="width: 100%; border-collapse: collapse;" border="1">
            <tbody>
            <tr style="height: 12px;">
            <td style="width: 60%; height: 20px;border-bottom:none;">&nbsp;Supplier : <span style="text-decoration:underline;">' . $supplier . '</span></td>
            <td style="width: 40%; height: 20px;border-bottom:none;">P.O No. : <span style="text-decoration:underline;">' . $ponum . '</span></td>
            </tr>
            <tr style="height: 12px;">  
            <td style="width: 60%; height: 20px;border-bottom:none;border-top:none;">&nbsp;Address : <span style="text-decoration:underline;">' . $supplier_address . '</span></td>
            <td style="width: 40%; height: 20px;border-bottom:none;border-top:none">Date : <span style="text-decoration:underline;">' . //$pdate . 
            '</span></td>
            </tr>
            <tr style="height: 12px;">  
            <td style="width: 60%; height: 20px;border-top:none;">&nbsp;TIN : <span style="text-decoration:underline;"></span></td>
            <td style="width: 40%; height: 20px;border-top:none;">Mode of Procurement : <span style="text-decoration:underline;">' . //$pdate . 
            '</span></td>
            </tr>
            <tr>
            <td colspan="6">
            <b>Gentlemen:</b><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please furnish this office the following articles subject to the terms and conditions contained them
            </td>
            </tr>
            <tr style="height: 12px;">
            <td style="width: 50%; height: 15px;border-bottom:none;">Place of Delivery : <span style="text-decoration:underline;">' . $model->place_of_delivery . '</span></td>
            <td style="width: 50%; height: 15px;border-bottom:none;">Delivery Term : <span style="text-decoration:underline;">' . $model->delivery_term . '</span></td>
            </tr>
            <tr style="height: 12px;">
            <td style="width: 50%; height: 15px;border-top:none;">Date of Delivery : <span style="text-decoration:underline;">' . $model->date_of_delivery . '</span></td>
            <td style="width: 50%; height: 15px;border-top:none;">Payment Term : <span style="text-decoration:underline;">' . $model->payment_term . '</span></td>
            </tr>
            </tbody>
            </table>

            <table style="width: 100%; border-collapse: collapse;" border="1";>
            <tbody>
            <tr>
            <td style="width: 10%; height: 5px; text-align: center;">Stock No.</td>
            <td style="width: 10%; height: 5px; text-align: center;">Unit</td>
            <td style="width: 40%; height: 5px; text-align: center;">Description</td>
            <td style="width: 13%; height: 5px; text-align: center;">Quantity</td>
            <td style="width: 13%; height: 5px; text-align: center;">Unit Cost</td>
            <td style="width: 13%; height: 5px; text-align: center;">Amount</td>
            </tr>
            <tr>
            <td style="width: 10%; height: 400px; text-align: center;">&nbsp;</td>
            <td style="width: 10%; height: 400px; text-align: center;">&nbsp;</td>
            <td style="width: 40%; height: 400px; text-align: center;">&nbsp;</td>
            <td style="width: 13%; height: 400px; text-align: center;">&nbsp;</td>
            <td style="width: 13%; height: 400px; text-align: center;">&nbsp;</td>
            <td style="width: 13%; height: 400px; text-align: center;">&nbsp;</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
            <td style="text-align: left;border-right:none;" colspan="4">' . strtoupper((fmod($summary, 1) !== 0.00) ?  Yii::$app->formatter->asSpellout(floor($summary)) . " AND " . substr(number_format($summary, 2), strlen(number_format($summary, 2)) - 2, strlen(number_format($summary, 2))) . "/100" : Yii::$app->formatter->asSpellout($summary)) . " PESOS ONLY" . '</td>
            <td style="border-left:none;text-align: right;" colspan="2">' . number_format($summary, 2) . '</td>
            </tr>
            </tfoot>
            </table>
            <table  style="width: 100%; border-collapse: collapse;" border="1"> 
            <tr>
            <td style="border-bottom:none;width: 100%; text-align: left;padding:15px;padding-top:0px;" colspan="6">&nbsp;In case of failure to make the full delivery within the time specified above, 
            a penalty of one-tenth (1/10) of one percent for every day of delay shall be imposed.
            </td>
            </tr>
            <tr>
            <td style=" text-align: left;border-top:none;border-bottom:none;border-right:none;padding:20px;" colspan="4">&nbsp;Conforme:</td>
            <td style=" text-align: left;border-top:none;border-bottom:none;border-left:none;" colspan="2">&nbsp;Very truly yours,</td>
            </tr>
            <tr>
            <td style="border-top:none;padding:3px;border-bottom:none;border-right:none;text-align: center;padding-left: 0px;" colspan="4">&nbsp;<span style="text-align:center;padding-left:0px;">_____________________________</span><br>Signature over printed name</td>

            <td style="border-top:none;padding:5px;border-bottom:none;border-left:none; text-align: center;" colspan="2">&nbsp;<span style="text-decoration:underline;text-align:center;"><b>' . $assig2 . '</b></span><br>Signature over Printed Name of Authorized Official</td>

            </tr>

            <tr>
            <td style="border-top:none;padding:3px;border-bottom:none;border-right:none;text-align: center;padding-left: 0px;" colspan="4">&nbsp;<span style="text-align:center;padding-left:0px;">_____________________________</span><br>Date</td>

            <td style="border-top:none;padding:5px;border-bottom:none;border-left:none; text-align: center;" colspan="2">&nbsp;<span style="text-decoration:underline;text-align:center;"><b>' . $Assig2Position . '</b></span><br>Designation</td>

            </tr>


            <tr>
            <td style=" text-align: left;padding:0px;border-bottom:none;" colspan="3">&nbsp;Funds Cluster: ___________________</td>
            <td style=" text-align: left;padding:5px;border-bottom:none;" colspan="3">&nbsp;ORS/BURS No. :&nbsp; __________________</td>
            </tr>
            <tr>
            <tr>
            <td style=" text-align: left;padding:0px;border-top:none;border-bottom:none;" colspan="3">&nbsp;Funds Available: ________________</td>
            <td style=" text-align: left;padding:5px;border-top:none;border-bottom:none" colspan="3">&nbsp;Date of the ORS/BURS:&nbsp; ___________</td>
            </tr>
            <tr>
            <td style=" text-align: center;padding:0px;border-bottom:none;border-top:none;" colspan="3"></td>
            <td style=" text-align: left;padding:5px;border-top:none;border-bottom:none;" colspan="3">&nbsp;Amount:&nbsp; __________________</td>
            </tr>
            <tr>
            <td style=" text-align: center;padding:0px;border-top:none;" colspan="3"><span style="text-decoration:underline;text-align:center;"><b>' . $assig1 . '</b></span><br><span style="font-size:11px;">Signature over Printed Name of Chief Accountant/Head of Accounting Division/Unit</span></td>
            <td style=" text-align: center;padding:0px;border-top:none;" colspan="3"></td>

            </tr>

            </table>';


        $footerss = '                      
        <table style="width:100%;">
        <tr>
            <td style="text-align: right;width:50%;">Page {PAGENO} of {nbpg}</td>
        </tr>              
        </table>';
        $pdf->options = [
            'title' => 'Report Title',
            'defaultheaderline' => 0,
            'defaultfooterline' => 0,
            'shrink_tables_to_fit' => 1,
            'subject' => 'Report Subject'
        ];
        $pdf->methods = [
            'SetHeader' => [$headers],
            'SetFooter' => [$footerss],
        ];

        return $pdf->render();
    }



    function getassig()
    {
        $con = Yii::$app->db;
        $sql = "	SELECT `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_1`) AS Assig1 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_1`) AS Assig1Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_2`) AS Assig2 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_2`) AS Assig2Position
	       	FROM `tbl_assignatory`
	WHERE `tbl_assignatory`.`assignatory_id` = 7";
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


    protected function findModelBidDetails($id)
    {
        if (($model = BidsDetails::findOne($id)) !== null) {
            return $model;
        } else {
            //return var_dump($model);
            //throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelPurchase($id)
    {
        if (($model = Purchaseorder::findOne($id)) !== null) {
            return $model;
        } else {
            //return var_dump($model);
            //throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
