<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Disbursement;
use common\models\procurement\DisbursementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\pdfprint;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;

/**
 * DisbursementController implements the CRUD actions for Disbursement model.
 */
class DisbursementController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Disbursement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisbursementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Disbursement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     *
     */

    public function GenerateNumber($ostype) {
        if ($ostype=="MDS") {
            $characters = "DV";
        }elseif ($ostype=="TF") {
            $characters = "TF";
        }elseif ($ostype=="ST") {
            $characters = "ST";
        }else{
            $characters = "BI";
        }
        $qry = "SELECT COUNT(`tbl_disbursement`.`dv_no`) + 1 AS NextNumber  FROM `fais-procurement`.`tbl_disbursement` WHERE LEFT(`tbl_disbursement`.`dv_no`,2) = '".$characters."'";
        $yr = date('Y');
        $mt = date('m');
        $con =  Yii::$app->db;
        $command = $con->createCommand($qry);
        $nextValue = $command->queryAll();
        foreach ($nextValue as $bbb) {
            $a = $bbb['NextNumber'];
        }
        $nextValue = $a;
        $documentcode = $characters."-".$yr."-".$mt."-";
        $documentcode=$documentcode.str_pad($nextValue, 4, '0', STR_PAD_LEFT);
        return $documentcode;
    }

    /**
     *
     */


    public function actionCheckimportid2()
    {
        $request = Yii::$app->request;
        $dv_num = $request->post('dv_num');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_order`.`purchase_order_number` ,CONCAT('TO PAYMENT of items to be delivered to DOST IX per P.O. No. ',`tbl_purchase_order`.`purchase_order_number`,
        ' dated ' , `tbl_purchase_order`.`purchase_order_date`) AS Particulars ";
        $sql = $sql.", SUM(`tbl_bids_details`.`bids_quantity` * `tbl_bids_details`.`bids_price`) AS Amount,
	    `tbl_purchase_order`.`purchase_order_date`
	    FROM `tbl_purchase_order` INNER JOIN `tbl_purchase_order_details`
	    ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
	    INNER JOIN `tbl_bids_details` ON 
	    `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
	    WHERE `tbl_purchase_order`.`purchase_order_number` = '".$dv_num."';";
        $checkxml = $con->createCommand($sql)->queryAll();
        return json_encode($checkxml);
    }

    /**
     *
     */


    function getDetails($id)
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM tbl_disbursement WHERE dv_id ='".$id."'";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    public function actionReportdv($id) {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $prdetails = $this->getDetails($id);
        $content = $this->renderPartial('_report', ['prdetails'=> $prdetails,'model'=>$model]);
        $pdf = new Pdf();
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;}h6 {  }';
        $supplier='';
        $ponum='';
        $prno='';
        $pdate='';
        $prdate='';
        foreach ($prdetails as $pr) {
            $payee = $pr["payee"];
            $dvno = $pr["dv_no"];
            $pdate = $pr["dv_date"];
            $prno = $pr["taxable"];
            $dvamount = $pr["dv_amount"];
        }
        $pdf->marginTop = 45;
        $pdf->marginBottom = 75;
        $pdf->marginFooter = 5;

        $headers= '
     ';
        $footerss= '<div style="height: 50px"></div>
                    <table border="0" width="100%">
                        <tr style="text-align: left;">
                          <td>'.$supplier.'</td>
                            <td style="text-align: right;">MARTIN A. WEE</td>
                        </tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                        <tr style="text-align: right;">
                            <td>ROBERT B. ABELLA</td>
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
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                        <tr><td></td><td></td></tr>                       
                    
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
                    'footer-style-left' => 300,
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
            'subject'=> 'Report Subject'];
        $pdf->methods = [
            'SetHeader'=>[$headers],
            'SetFooter'=>[$footerss],
        ];

        return $pdf->render();
    }



    public function actionCheckimportid()
    {
        $request = Yii::$app->request;
        $so_num = $request->post('so_num');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_obligationrequest` WHERE os_no= '".$so_num."';";
        $checkxml = $con->createCommand($sql)->queryAll();
        return json_encode($checkxml);
    }

    /**
     * Creates a new Disbursement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $dvtype_data = [
            "MDS" => "MDS 101",
            "TF" => "Trust Fund",
            "ST" => "S & T Scholarship Fund",
            "BI" => "B I R Taxes",
        ];
        $dbursement = new Disbursement();
        $con =  Yii::$app->db;
        $command_employee = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`lastname`,', ', `tbl_profile`.`firstname` ,' ', `tbl_profile`.`middleinitial`, ' - ' , `tbl_profile`.`designation`) AS employeename
        FROM `tbl_profile`");
        $command_so = $con->createCommand("SELECT `tbl_obligationrequest`.`os_no` FROM `fais-procurement`.`tbl_obligationrequest`");
        $employees = $command_employee->queryAll();
        $sono=$command_so->queryAll();
        $command_po = $con->createCommand("SELECT `tbl_purchase_order`.`purchase_order_number` FROM `fais-procurement`.`tbl_purchase_order`");
        $ponum = $command_po->queryAll();
        $listEmployee = ArrayHelper::map($employees, 'user_id', 'employeename');
        $listSono = ArrayHelper::map($sono, 'os_no', 'os_no');
        $listPono = ArrayHelper::map($ponum, 'purchase_order_number', 'purchase_order_number');
        if ($dbursement->load(Yii::$app->request->post())) {
            if ($dbursement->validate()) {
                $dvnumber = $this->GenerateNumber($dbursement->dv_type);
                $dbursement->dv_no = $dvnumber; //'PR-13-01-0028';
               // $dbursement->particulars = $_POST["particulars"];
                $dbursement->save();
                //return $osnumber;
                return $this->redirect('index');
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $dbursement->errors;
                return $errors;
            }

        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $dbursement,
                    'dvtype_data' => $dvtype_data,
                    'listEmployee'=>$listEmployee,
                    'listSono'=>$listSono,
                    'listPono'=>$listPono,
                ]);
            }else{
                return $this->render('create', [
                    'model' => $dbursement,
                    'dvtype_data' => $dvtype_data,
                    'listEmployee'=>$listEmployee,
                    'listSono'=>$listSono,
                    'listPono'=>$listPono,
                ]);
            }
        }
    }

    /**
     * Updates an existing Disbursement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = new Disbursement();
        $dvtype_data = [
            "MDS" => "MDS 101",
            "TF" => "Trust Fund",
            "ST" => "S & T Scholarship Fund",
            "BI" => "B I R Taxes",
        ];
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        if($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if(isset($_POST['btnUpdatePrint'])) {
                    return $this->redirect('reportob?id='.$id);
                }else{
                    return $this->redirect('index');
                }
            } else {
                $con =  Yii::$app->db;
                $command_employee = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`lastname`,', ', `tbl_profile`.`firstname` ,' ', `tbl_profile`.`middleinitial`, ' - ' , `tbl_profile`.`designation`) AS employeename
        FROM `tbl_profile`");
                $command_so = $con->createCommand("SELECT `tbl_obligationrequest`.`os_no` FROM `fais-procurement`.`tbl_obligationrequest`");
                $employees = $command_employee->queryAll();
                $sono=$command_so->queryAll();
                $command_po = $con->createCommand("SELECT `tbl_purchase_order`.`purchase_order_number` FROM `fais-procurement`.`tbl_purchase_order`");
                $ponum = $command_po->queryAll();
                $listEmployee = ArrayHelper::map($employees, 'user_id', 'employeename');
                $listSono = ArrayHelper::map($sono, 'os_no', 'os_no');
                $listPono = ArrayHelper::map($ponum, 'purchase_order_number', 'purchase_order_number');
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'dvtype_data' => $dvtype_data,
                    'listEmployee'=>$listEmployee,
                    'listSono'=>$listSono,
                    'listPono'=>$listPono,
                ]);
            }
        }
    }

    /**
     * Deletes an existing Disbursement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Disbursement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Disbursement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Disbursement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
