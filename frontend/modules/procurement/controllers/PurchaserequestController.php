<?php

namespace frontend\modules\procurement\controllers;

use common\models\procurement\Assignatory;
use common\models\procurement\Purchaserequestdetails;
use Yii;
use common\modules\admin\models\User;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaserequestSearch;

use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

$session = Yii::$app->session;
$model = new Purchaserequest();

/**
 * PurchaserequestController implements the CRUD actions for Purchaserequest model.
 */
class PurchaserequestController extends Controller
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
     * Lists all Purchaserequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaserequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /***
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $request = Yii::$app->request;
        if ($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            $prdetails = $this->getprDetails($model->purchase_request_id);
            return $this->renderAjax('_view', [
                'model' => $model,
                'prdetails' => $prdetails,
            ]);
        }
    }

    public function actionTag()
    {
        $request = Yii::$app->request;
        if ($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            $prdetails = $this->getprDetails($model->purchase_request_id);
            return $this->renderAjax('_tag', [
                'model' => $model,
                'prdetails' => $prdetails,
            ]);
        }
    }

    public function actionApprove()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id');
            $model = $this->findModel($id);
            $model->status = 1;
            $model->tag_user_id = Yii::$app->user->id;
            $model->date_tag = date("Y-m-d H:i:s");
            $model->save();
        }
    }
    public function actionDisapprove()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id');
            $model = $this->findModel($id);
            $model->status = 2;
            $model->tag_user_id = Yii::$app->user->id;
            $model->date_tag = date("Y-m-d H:i:s");
            $model->save();
        }
    }

    public function actionReview()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id');
            $model = $this->findModel($id);
            $model->status = 3;
            $model->pap_code = $_POST['pap'];
            $model->tag_user_id = Yii::$app->user->id;
            $model->date_tag = date("Y-m-d H:i:s");
            $model->save();
        }
    }

    public function actionReportpr($id)
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $prdetails = $this->getprDetails($model->purchase_request_id);
        $content = $this->renderPartial('_report', ['prdetails' => $prdetails, 'model' => $model]);
        $pdf = new Pdf();
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = 'body {}.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;}h6 {  }';
        $pdf->marginFooter = 5;

        $requested_by = "";
        $requested_by_position = "";
        $approved_by = "";
        $approved_by_position = "";

        foreach ($prdetails as $pr) {
            $requested_by = $pr["requested_by"];
            $requested_by_position = $pr["requested_by_position"];
            $approved_by = $pr["approved_by"];
            $approved_by_position = $pr["approved_by_position"];
        }


        $pdf->marginTop = 75;
        $pdf->marginBottom = 90;

        $headers = '<div style="height: 135px;"></div>
                        <table width="100%">
                            <tr class="nospace-border">
                                <td width="60%" style="padding-left: 55px;">Department of Science And Technology</td>
                                <td width="30%" style="padding-left: 65px;">' . $model->purchase_request_number . '</td>
                                <td width="10%">' . date("m-d-Y", strtotime($model->purchase_request_date)) . '</td>
                            </tr>
                        </table>';
        $LeftFooterContent = '
<table style="width: 50%;" border="0" cellpadding="0">
                                <tbody>
                                <tr>
                                <td><h6>' . strtoupper($model->purchase_request_purpose) . '</h6></td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td><h6>Project Reference No. : ' . $model->purchase_request_referrence_no . '</h6>
                                </td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td><h6>Project Name : ' . $model->purchase_request_project_name . '</h6></td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td><h6>Project Location : ' . $model->purchase_request_location_project . '</h6></td>
                                <td>&nbsp;</td>
                                </tr>
                                </tbody>
                                </table>';
        $s = "";
        $x = 0;
        while ($x < 10) {
            $x++;
            $s = $s . '<tr class="nospace-border">
                      <td width="50%" style="text-align: right;padding-left: 50px;"></td>
                      <td width="50%" style="text-align: right;padding-right: 100px;"></td>
                     </tr>';
        }
        $LeftFooterContent =
            $LeftFooterContent . '<table width="100%">
                                    ' . $s . '
                                    <tr class="nospace-border">
                                        <td width="50%" style="text-align: center;padding-left: 120px;"><b>' . $requested_by . '</b><br/>' . $requested_by_position . '</td>
                                        <td width="50%" style="text-align: center;padding-left: 75px;"><b>' . $approved_by . '</b><br/>' . $approved_by_position . '</td>
                                    </tr>
                                    <tr><td></td><td></td></tr>
                                                                                                                                                            
                                    <tr style="text-align: right;">
                                         <td>' . date("F j, Y") . '</td>
                                         <td style="text-align: right;">Page {PAGENO} of {nbpg}</td>
                                    </tr>    
                                  </table>';

        $pdf->options = [
            'title' => 'Report Title',
            'defaultheaderline' => 0,
            'defaultfooterline' => 0,
            'subject' => 'Report Subject'
        ];
        $pdf->methods = [
            'SetHeader' => [$headers],
            'SetFooter' => [$LeftFooterContent],
        ];

        return $pdf->render();
    }





    public function actionReportprfull($id)
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $prdetails = $this->getprDetails($model->purchase_request_id);
        $content = $this->renderPartial('_report2', ['prdetails' => $prdetails, 'model' => $model]);
        $pdf = new Pdf();
        $pdf->mode = Pdf::MODE_UTF8;
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = 'body {} .kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-family:Arial;}';
        $pdf->marginFooter = 5;

        $requested_by = "";
        $requested_by_position = "";
        $approved_by = "";
        $approved_by_position = "";
        $assig = $this->findAssignatory(1);
        foreach ($prdetails as $pr) {
            $requested_by = $pr["requested_by"];
            $requested_by_position = $pr["requested_by_position"];
            $approved_by = $pr["approved_by"];
            $approved_by_position = $pr["approved_by_position"];
            $section = $pr["section"];
            $responsibility_center_code = $pr['responsibility_center_code'];
            $division = $pr["division"];
        }


        $pdf->marginTop = 65;
        $pdf->marginBottom = 65;

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
        <p><h6 style-P><strong>FASS-PUR F05</strong>&nbsp; Rev. 1/07-01-23</h6></p>
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
        <td style="text-align: center;font-family:Arial;font-size:15px;border-top:none;" colspan="2"><b>PURCHASE REQUEST</b></td>
        </tr>
        <tr>
        <td style="text-align: center;font-family:Arial;font-size:15px;border-top:none;"><br></td>
        </tr>
        <tr>        
        <td style="text-align: center;border-bottom:none;border-top:none;">&nbsp;</td>
        </tr>
        <tr>
        <td style="text-align: left;border-bottom:none;border-top:none;">Entity Name:&nbsp;<u>Department of Science and Technology - IX</u></td>
                <td style="text-align: right;border-bottom:none;border-top:none;">Fund Cluster: __________________</td>
        </tr>
        </tbody>                                                                                                                                                                                                                                                                                                                                                             
        </table>
<table style="width: 100%; border-collapse: collapse;" border="1">
        <tbody>
        <tr>
        <td style="width: 40%; height: 12.6667px;" rowspan="2">Office/Section: <span style="text-decoration: underline;">' . $section . '</span></td>
        <td style="width: 20%; ">PR No. <span style="text-decoration: underline;">' . $model->purchase_request_number . '</span></td>
        <td style="width: 20%; height: 12.6667px;" rowspan="2">Date : <span style="text-decoration:underline;">' . date("m-d-Y", strtotime($model->purchase_request_date)) . '</span></td>
        </tr>
        <tr>
        <td style="width: 25%; height: 12px;">Responsibility Center Code : <u>'. $responsibility_center_code .'</u></td>
        </tr>
        </tbody>
        </table>
        
        <table border="1" style="border-collapse: collapse;font-size:12px;width:100%;">
                <tr>
                    <td style="width: 10%; height: 12px; text-align: center;">
                        <p>Stock No.</p>
                    </td>
                    <td style="width: 10%; height: 12px; text-align: center;">Unit</td>
                    <td style="text-align: center; width: 50%;">Item Desription</td>
                    <td style="width: 10%; height: 12px; text-align: center;">Quantity</td>
                    <td style="width: 10%; height: 12px; text-align: center;">Unit Cost</td>
                    <td style="width: 10%; height: 12px; text-align: center;">Total Cost</td>
                </tr>
                <tr>
                    <td style="height: 650px;"></td>
                    <td style="height: 650px;"></td>
                    <td style="height: 650px;"></td>    
                    <td style="height: 650px;"></td>
                    <td style="height: 650px;"></td>
                    <td style="height: 650px;"></td>
                </tr>
                <tr>
                    <td colspan="6" style="height: 110px;">' .$model->purchase_request_purpose. '</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top:none;border-bottom:none; border-right:none;"></td>
                    <td style="border-bottom:none; border-left:none; border-right:none;">Requested By:</td>
                    <td style="border-bottom:none; border-left:none;" colspan="3">Approved By:</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top:none;border-bottom:none; border-right:none;">Signature:</td>
                    <td style="border-top:none;border-bottom:none; border-left:none; border-right:none">______________________________</td>
                    <td colspan="3" style="border-top:none;border-bottom:none; border-left:none">______________________________</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top:none;border-bottom:none; border-right:none;">Printed Name:</td>
                    <td style="border-top:none;border-bottom:none; border-left:none; border-right:none;"><u><b>'.$pr['requested_by'].'</b></u></td>
                    <td colspan="3" style="border-top:none;border-bottom:none; border-left:none;"><u><b>'.$pr['approved_by'].'</b></u></td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top:none; border-right:none;">Designation:</td>
                    <td style="border-top:none; border-left:none; border-right:none;"><u>'.$pr['requested_by_position'].'</u></td>
                    <td colspan="3" style="border-top:none; border-left:none;"><u>'.$pr['approved_by_position'].'</u></td>
                </tr>   
        </table>';
        //         $LeftFooterContent = '
        //         <div style="height:0px;"></div>
        // <table style="width: 118%;font-size:7px;">
        //                                 <tbody>
        //                                 <tr>
        //                                 <td style="padding-left:10px;"><h6>Purpose : ' . $model->purchase_request_purpose . '</h6></td>
        //                                 <td>&nbsp;</td>
        //                                 </tr>
        //                                 <tr>
        //                                 <td style="padding-left:10px;"><h6>Project Reference No. : ' . $model->purchase_request_referrence_no . '</h6></td>
        //                                 </tr>
        //                                 <tr>
        //                                 <td style="padding-left:10px;"><h6>Project Name : ' . $model->purchase_request_project_name . '</h6></td>
        //                                 <td>&nbsp;</td>
        //                                 </tr>
        //                                 <tr>
        //                                 <td style="padding-left:10px;"><h6>Project Location : ' . $model->purchase_request_location_project . '</h6></td>
        //                                 <td>&nbsp;</td>
        //                                 </tr>
        //                                 <tr>
        //                                 <td style="padding-left:10px;"><h6>PAP Code : ' . $model->pap_code . '</h6></td>
        //                                 <td>&nbsp;</td>
        //                                 </tr>
        //                                 </tbody>    
        //                                 </table>';
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
            'subject' => 'Report Subject'
        ];
        $pdf->methods = [
            'SetHeader' => [$headers],
            'SetFooter' => [$footerss],
            // 'SetFooter' => [$LeftFooterContent],
        ];

        return $pdf->render();
    }





    /**
     * @return string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $prequest = new Purchaserequest();
        $preqdetails = new Purchaserequestdetails();
        $session = Yii::$app->session;
        if ($prequest->load(Yii::$app->request->post()) || $preqdetails->load(Yii::$app->request->post())) {
            //*************Saving Record Here
            if ($prequest->validate() && $preqdetails->validate()) {
                $connection =  Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $lineitembudget = $prequest->lineitembudgetlist;
                $arr = json_decode($lineitembudget, true);
                try {
                    // all inputs are valid

                    $prnumber = $this->GeneratePRNumber();
                    $prequest->purchase_request_number = $prnumber; //'PR-13-01-0028';
                    $prequest->user_id = yii::$app->user->getId();
                    $prequest->save();
                    $data = array();
                    foreach ($arr as $budgets) {
                        $details = $budgets["Detail#"];
                        $unit = $budgets["Unit"];
                        $unit_type = $budgets["unit_type"];
                        $itemdescription = $budgets["Item Description"];
                        $quantity = $budgets["Quantity"];
                        $unitcost = $budgets["Unit Cost"];
                        $totalCost = $budgets["Total Cost"];
                        $data[] =  [$prequest->purchase_request_id, $itemdescription, $quantity, $unitcost, $unit_type];
                    }
                    $connection->createCommand()->batchInsert('fais-procurement.tbl_purchase_request_details', ['purchase_request_id', 'purchase_request_details_item_description', 'purchase_request_details_quantity', 'purchase_request_details_price', 'unit_id'], $data)
                        ->execute();
                    $transaction->commit();
                    $session->set('savepopup', "executed");
                    return $this->redirect('index');
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $session->set('errorpopup', "executed");
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    $session->set('errorpopup', "executed");
                    throw $e;
                }
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $prequest->errors;
            }
        } else {
            $assig = $this->findAssignatory(1);
            return $this->renderAjax('_modal', [
                'model' => $prequest,
                'assig' => $assig,
            ]);
        }
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate()
    {
        $model = new Purchaserequest();
        $session = Yii::$app->session;
        $request = Yii::$app->request;

        if ($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $connection =  Yii::$app->db;
                $lineitembudget = $model->lineitembudgetlist;
                $data = array();
                $arr = json_decode($lineitembudget, true);
                foreach ($arr as $budgets) {
                    $details = $budgets["Detail#"];
                    $unit = $budgets["Unit"];
                    $unit_type = $budgets["unit_type"];
                    $itemdescription = $budgets["Item Description"];
                    $quantity = $budgets["Quantity"];
                    $unitcost = $budgets["Unit Cost"];
                    $totalCost = $budgets["Total Cost"];

                    if ($details == "-1") {
                        $data[] =  [$model->purchase_request_id, $itemdescription, $quantity, $unitcost, $unit_type];
                        $connection->createCommand()->batchInsert('fais-procurement.tbl_purchase_request_details', ['purchase_request_id', 'purchase_request_details_item_description', 'purchase_request_details_quantity', 'purchase_request_details_price', 'unit_id'], $data)
                            ->execute();
                    } else {
                        Purchaserequestdetails::updateAll(['purchase_request_details_item_description' => $itemdescription, 'purchase_request_details_quantity' => $quantity, 'purchase_request_details_price' => $unitcost, 'unit_id' => $unit_type], 'purchase_request_details_id = ' . $details);
                    }
                }

                $session->set('updatepopup', "executed");
                return $this->redirect(['index']);
                $this->redirect('index');
            } else {
                $assig = $this->findAssignatory(1);
                return $this->renderAjax('_modal', [
                    'model' => $model,
                    'assig' => $assig,
                ]);
            }
        }
    }

    /**
     *
     */

    public function actionCheckprdetails()
    {
        $pr = Yii::$app->request;
        $pr_no = $pr->get('pno');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT *,`fais`.`fnGetUnits`(`tbl_purchase_request_details`.`unit_id`) units FROM `fais-procurement`.`tbl_purchase_request_details` INNER JOIN `tbl_purchase_request` ON `tbl_purchase_request`.`purchase_request_id` = `tbl_purchase_request_details`.`purchase_request_id` 
        WHERE `tbl_purchase_request`.`purchase_request_number` = '" . $pr_no . "'";
        $prdetails = $con->createCommand($sql)->queryAll();
        $data = array();
        $x = 0;
        foreach ($prdetails as $pr) {
            $x++;
            $data[] = [
                'purchase_request_details_id' => $pr["purchase_request_details_id"],
                'purchase_request_id' => $pr["purchase_request_id"],
                'unit_id' => $pr["unit_id"],
                'purchase_request_details_item_description' => $pr["purchase_request_details_item_description"],
                'purchase_request_details_quantity' => $pr["purchase_request_details_quantity"],
                'purchase_request_details_price' => $pr["purchase_request_details_price"],
                'units' => $pr["units"],
            ];
        }
        return json_encode($data);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $session = Yii::$app->session;
        $session->set('deletepopup', "executed");
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     *
     */

    public function actionDeletedetails()
    {
        $pr = Yii::$app->request;
        $session = Yii::$app->session;
        $pr_no = $pr->get('idno');
        $this->findModelDetails($pr_no)->delete();
        return 'success';
    }

    /**
     * Finds the Purchaserequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchaserequest the loaded model
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

    /**
     *
     */

    protected function findModelDetails($id)
    {
        if (($model = Purchaserequestdetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function findAssignatory($id)
    {
        if (($model = Assignatory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * @return string
     * @throws \yii\db\Exception
     */

    public function GeneratePRNumber()
    {
        $characters = "PR";
        $yr = date('y');
        $mt = date('m');
        $gg = date('Y');
        $con =  Yii::$app->db;
        $command = $con->createCommand("SELECT MAX(SUBSTR(`tbl_purchase_request`.`purchase_request_number`,10)) + 1 AS NextNumber FROM `fais-procurement`.`tbl_purchase_request`
        WHERE YEAR(`tbl_purchase_request`.`purchase_request_date`) =" . $gg);
        $nextValue = $command->queryAll();
        foreach ($nextValue as $bbb) {
            $a = $bbb['NextNumber'];
        }
        $nextValue = $a;
        $documentcode = $characters . "-" . $yr . "-" . $mt . "-";
        $documentcode = $documentcode . str_pad($nextValue, 4, '0', STR_PAD_LEFT);
        return $documentcode;
    }


    function getprDetails($id)
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT *,
                    `fnGetAssignatoryName`(`tbl_purchase_request`.`purchase_request_requestedby_id`) AS requested_by,
                    `fnGetAssignatoryPosition`(`tbl_purchase_request`.`purchase_request_requestedby_id`) AS requested_by_position,
                    `fnGetAssignatoryName`(`tbl_purchase_request`.`purchase_request_approvedby_id`) AS approved_by,
                    `fnGetAssignatoryPosition`(`tbl_purchase_request`.`purchase_request_approvedby_id`) AS approved_by_position,
                    `fnGetSection`(`tbl_purchase_request`.`section_id`) AS section,
                    (SELECT `tbl_section`.`responsibility_center_code` FROM `fais`.`tbl_section` WHERE `tbl_section`.`section_id`=`tbl_purchase_request`.`section_id`) AS responsibility_center_code, 
                    `fnGetDivision`(`tbl_purchase_request`.`division_id`) AS division
                    FROM `tbl_purchase_request_details` 
                    INNER JOIN `tbl_purchase_request`
                    ON `tbl_purchase_request`.`purchase_request_id` = `tbl_purchase_request_details`.`purchase_request_id`
                    INNER JOIN `fais`.`tbl_unit_type`
                    ON `tbl_unit_type`.`unit_type_id` = `tbl_purchase_request_details`.`unit_id`
                WHERE `tbl_purchase_request_details`.`purchase_request_id`=" . $id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    public function actionImport()
    {
        $pr_objPHPExcel = \PHPExcel_IOFactory::load(Yii::getAlias('@data') . '/tblPRID.xlsx');
        $pr_sheetData = $pr_objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $prDetails_objPHPExcel = \PHPExcel_IOFactory::load(Yii::getAlias('@data') . '/tblPurchaseRequest.xlsx');
        $prDetails_sheetData = $prDetails_objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $purchase_requests = $this->buildPRArray($pr_sheetData);
        $purchase_request_details = $this->buildPRDetailsArray($prDetails_sheetData);
        $new_PRs = $this->mergeData($purchase_requests, $purchase_request_details);

        return $this->render('import', [
            'purchase_requests' => $purchase_requests,
            'purchase_request_details' => $purchase_request_details,
            'prDetails_sheetData' => $prDetails_sheetData,
            'new_PRs' => $new_PRs,
        ]);
    }

    function mergeData($purchase_requests, $purchase_request_details)
    {
        $new_PRs = [];
        /*foreach($purchase_requests as $pr){
            $key = array_search($pr['purchase_request_number'], array_column($purchase_request_details, 'purchase_request_number'));
            array_push($pr['pr_details'], $purchase_request_details[$key]);
            array_push($new_PRs, $pr);
        }*/

        foreach ($purchase_request_details as $pr_details) {
            $key = null;
            $key = array_search($pr_details['purchase_request_number'], array_column($purchase_requests, 'purchase_request_number'));
            array_push($new_PRs, $purchase_requests[$key]);
            array_push($new_PRs[$key]['pr_details'], $pr_details);
        }
        return $new_PRs;
    }

    function buildPRArray($sheetData)
    {
        $purchase_requests = [];

        foreach ($sheetData as $pr) {
            $userDetails = $this->getUserDetails($pr['P'], $pr['I']);
            $date_array = explode('-', $pr['C']);


            if (isset($date_array[1])) {
                $pr_date = date('Y-m-d', strtotime('20' . $date_array[2] . '-' . $date_array[0] . '-' . $date_array[1]));
                if (date('Y', strtotime($pr_date)) == 2019) {
                    $pr_array = [
                        'purchase_request_id' => '',
                        'purchase_request_number' => $pr['B'], //PR_No
                        'purchase_request_sai_number' => $pr_date,
                        'division_id' => $userDetails['division_id'], //Department
                        'section_id' => $userDetails['section_id'], //Section
                        'purchase_request_date' => $pr_date, //PR_Date
                        'purchase_request_saidate' => '',
                        'purchase_request_purpose' => $pr['H'], //PR_Purpose
                        'purchase_request_referrence_no' => $pr['M'], //ProjRef_No
                        'purchase_request_project_name' => $pr['N'], //ProjName
                        'purchase_request_location_project' => $pr['O'], //
                        'purchase_request_requestedby_id' => $pr['D'], //PR_REQ
                        'purchase_request_approvedby_id' => $pr['F'], //PR_APPROV
                        'user_id' => $userDetails['user_id'], //User
                        'pr_details' => []
                    ];

                    array_push($purchase_requests, $pr_array);
                }
            }
        }

        return $purchase_requests;
    }

    function buildPRDetailsArray($sheetData)
    {
        $purchase_request_details = [];

        foreach ($sheetData as $pr_details) {
            /**(
                [A] => PR_No
                [B] => Item_No
                [C] => Unit
                [D] => Item_Description
                [E] => Quantity
                [F] => UnitCost
                [G] => TotalCost
            )**/
            $pr_details_array = [
                'purchase_request_details_id' => '',
                'purchase_request_id' => $pr_details['A'],
                'purchase_request_number' => $pr_details['A'],
                'purchase_request_details_unit' => $pr_details['C'],
                'unit_id' => $pr_details['C'],
                'purchase_request_details_item_description' => $pr_details['D'],
                'purchase_request_details_quantity' => $pr_details['E'],
                'purchase_request_details_price' => $pr_details['F'],
                'purchase_request_details_status' => 0
            ];

            array_push($purchase_request_details, $pr_details_array);
        }

        return $purchase_request_details;
    }

    function getUserDetails($user, $division)
    {
        $userDetails = User::find()
            ->where(['LIKE', 'username', substr($user, 0, 3)])
            ->one();

        $details = [
            'user_id' => $userDetails['user_id'],
            'username' => $userDetails['username'],
            'division_id' => '',
            'section_id' => ''
        ];

        return $details;
    }

    public function actionReportquotation($id)
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $supplier = '';
        $address = '';
        $sub = '';
        $employee = '';
        //$employee = explode("|",$employee);
        $sub = '';
        $model = $this->findModel($id);
        $prdetails = $this->getprDetails2($model->purchase_request_id);
        $content = $this->renderPartial('_reportquotations', ['prdetails' => $prdetails, 'model' => $model]);
        $assig =$this->findAssignatory(3);
        $pdf = new Pdf();
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination = $pdf::DEST_BROWSER;
        //$sub = date('F j, Y',$sub);
        $pdf->content = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;} h1 {border-bottom: 2px solid blackfont-weight:normal;margin-bottom:5px;width: 140px;}';
        $pdf->marginTop = 157;
        $pdf->marginBottom = 50;
        $headers = '
        <table width="100%">
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
    <p><h6><strong>FASS-PUR F06</strong>&nbsp; Rev. 0/ 08-16-07</h6></p>
    </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    
    <table style="width: 100%;">
    <tbody>
    <tr>
    <td style="text-align: center;">Republic of the Philippines</td>
    </tr>
    <tr>        
    <td style="text-align: center;"><strong>'.$assig->CompanyTitle.'</strong></td>
    </tr>
    <tr>
    <td style="text-align: center;">'.$assig->RegionOffice.'</td>
    </tr>
    <tr>
    <td style="text-align: center;">'.$assig->Address.'</td>
    </tr>
    <tr>
    <td style="text-align: center;">&nbsp;</td>
    </tr>
    </tbody>
    </table>
    
    <table style="width: 100%;">
    <tbody>
    <tr style="height: 12px;">
    <td style="height: 12px; width: 50%;font-size:11px;">Standard Form Number&nbsp;: SF-GOOD-60</td>
    <td style="height: 12px; width: 50%;font-size:11px;">Project Ref.No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
    if($model->purchase_request_referrence_no=='') {
        $headers=$headers.'_______________________________________';
    }else{
        $headers=$headers.'<span style="text-decoration:underline;">'.$model->purchase_request_referrence_no.'</span>';
    }
    
    $headers=$headers.'</td>
    </tr>
    <tr style="height: 12.6364px;">
    <td style="height: 12.6364px; width: 50%;font-size:11px;">Revised on&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: May 24, 2004&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
    <td style="height: 12.6364px;font-size:11px;">Name of the Project&nbsp; &nbsp; &nbsp; &nbsp;: ';
    if($model->purchase_request_project_name=='') {
        $headers=$headers.'_______________________________________';
    }else{
        $headers=$headers.'<span style="text-decoration:underline;">'.$model->purchase_request_project_name.'</span>';
    }
    
    $headers=$headers.'</td>
    </tr>
    <tr style="height: 12px;">
    <td style="width: 50%; height: 12px;font-size:11px;">Standard Form Title&nbsp; &nbsp; &nbsp; &nbsp;: <span style="text-decoration: underline;">REQUEST FOR QUOTATION</span></td>
    <td style="height: 12px;font-size:11px;">Location of the Project&nbsp; &nbsp;: ';
    if($model->purchase_request_location_project=='') {
        $headers=$headers.'_______________________________________';
    }else{
        $headers=$headers.'<span style="text-decoration:underline;">'.$model->purchase_request_location_project.'</span>';
    }
    
    $headers=$headers.'</td>
    </tr>
    <tr style="height: 12px;">
    <td style="height: 12px;">&nbsp;</td>
    <td style="height: 12px;">&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
    <td style="height: 12px;font-size:11px;">';
    
    if($supplier=='') {
        $headers=$headers.'_______________________________________';
    }else{
         $headers=$headers.'<span style="text-decoration:underline;">'.strtoupper($supplier).'</span>';
    }
    
    $headers=$headers.'</td>
    
    
    <td style="height: 12px;">&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
    <td style="height: 12px;font-size:11px;">';
    
    if($address=='') {
        $headers=$headers.'_______________________________________';
    }else{
        $headers=$headers.'<span style="text-decoration:underline;">'.$address.'</span>';
    }
    
    $headers=$headers.'</td>
    
    <td style="height: 12px;">&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
    <td style="height: 12px;font-size:11px;" colspan="2"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please quote  your  lowest   price  on  the   item/s  listed   below,  subject  to  the   General   Conditions   on   the  last page,  stating  the   shortest   time  of   delivery
      and   submit   your  quotation  duly   signed  by  your  representative   not   later than <span style="text-decoration:underline;">'.$sub.'</span> in an envelope.</p></td>
    </tr>
    </tbody>
    </table>
    <div style="height:30px;"></div>
    <table style="width:100%;">
    <tr>
    <td style="width:80%;"></td>
    <td style="width:20%;text-align:center;"><td>
    </tr>
        <tr>
            <td style="width:80%;"></td>
            <td style="width:35%;font-size:13px;text-align:center;"><b>Ronnel B. Gundoy</b><br/>Supply Officer<td>
        </tr>
    </table>
    <div style="height:25px;"></div>
    <table style="width: 100%;">
    <tbody>
    <tr>
    <td style="width: 10%;font-size:10px; vertical-align: top; text-align: left;">Note:</td>
    <td style="width: 90%;font-size:10px;line-space:11px; vertical-align: top;">
    <p>&nbsp;<strong>1</strong><strong>. ALL ENTRIES MUST BE TYPEWRITTEN</strong></p>
    <p><strong>&nbsp;2</strong><strong>.</strong><strong> DELIVERY PERIOD WITHIN _________ CALENDAR DAYS</strong></p>
    <p><strong>&nbsp;3</strong><strong>. WARRANTY SHALL</strong> <strong>B</strong><strong>E</strong> <strong>F</strong><strong>OR</strong> <strong>A</strong> <strong>P</strong><strong>ERIOD</strong> <strong>O</strong><strong>F</strong><strong> SIX (6) MONTHS FOR SUPPLIES &amp; MATERIALS, ONE(1) YEAR FOR EQUIPMENT, FROM DATE OF&nbsp; &nbsp; &nbsp;<strong>ACCEPTANCE BY THE PROCURING ENTITY.</strong></strong></p>
    <p><strong>&nbsp;4. PRICE VALIDITY</strong> <strong>S</strong><strong>HALL BE FOR</strong><strong> A PERIOD OF _________ CALENDAR DAYS.</strong></p>
    <p><strong>&nbsp;5. G-EPS</strong> <strong>R</strong><strong>EGISTRATION</strong><strong> CERTIFICATE SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION</strong></p>
    <p><strong>&nbsp;6</strong><strong>. BIDDERS</strong> <strong>SH</strong><strong>A</strong><strong>LL SUBMIT</strong><strong> ORIGINAL BROCHURES SHOWING CERTIFICATIONS OF THE PRODUCT BEING OFFERED</strong></p>
    </td>
    </tr>
    </tbody>
    </table>
    
    <div style="height:35px;"></div>
    <table style="width:100%;border-collapse:collapse;" border="1">
    <thead>
        <tr>
            <th style="text-align:center;font-size:11px;"> Item/s No.</th>
            <th style="text-align:center;font-size:11px;"> Item/s Description.</th>
            <th style="text-align:center;font-size:11px;"> Qty.</th>
            <th style="text-align:center;font-size:11px;"> Unit Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="10%" style="height:375px;"></td>
            <td width="60%" style="height:375px;"></td>
            <td width="15%" style="height:375px;"></td>
            <td width="15%" style="height:375px;"></td>
        </td>
    </tbody>
    </table>
    
    <div style="height:10px"></div>
    
    <table style="width: 100%;">
    <tbody>
    <tr style="height: 12px;">
    <td style="width: 45.0699%; height: 12px;font-size:9px;">Brand and Model&nbsp;:  __________________________________________</td>
    <td style="width: 53.5839%; height: 12px;font-size:9px;">&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
    <td style="width: 45.0699%; height: 12px;font-size:9px;">Delivery Period &nbsp;&nbsp;&nbsp;: __________________________________________</td>
    <td style="width: 53.5839%; height: 12px;font-size:9px;">&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
    <td style="width: 45.0699%; height: 12px;font-size:9px;">Warranty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: __________________________________________ </td>                                                                                                                                                           </td>
    <td style="width: 53.5839%; height: 12px;font-size:9px;">&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
    <td style="width: 45.0699%; height: 12px;font-size:9px;">Price Validity&nbsp; &nbsp; &nbsp; &nbsp;: __________________________________________</td>
    <td style="width: 53.5839%; height: 12px;font-size:9px; text-align: right;">&nbsp;</td>
    </tr>
    <tr style="height: 12.7273px;">
    <td style="width: 45.0699%; height: 12.7273px;font-size:9px;">&nbsp;</td>
    <td style="width: 53.5839%; height: 12.7273px;font-size:9px; text-align: right;">___________________________________________________</td>
    </tr>
    <tr style="height: 12.7273px;">
    <td style="width: 45.0699%; height: 12.7273px;font-size:9px;">&nbsp;</td>
    <td style="width: 53.5839%; height: 12.7273px;font-size:9px; text-align: center;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Supplier</td>
    </tr>
    </tbody>
    </table>
    
    
        ';
    
    
        $LeftFooterContent = '<div style="text-align: left;font-weight: bold;font-size:11px;">' . $model->purchase_request_number . '</div><div style="text-align: left;font-size:11px;font-weight: lighter"><span style="font-size:11px;">'.date("F j, Y").'</span></div>';
        $RightFooterContent = '<div style="text-align: right;padding-top:-50px;font-size:11px;">Page {PAGENO} of {nbpg}</div>';
        $oddEvenConfiguration =
            [
                'L' => [ // L for Left part of the header
                    'content' => $LeftFooterContent,
                ],
                'C' => [ // C for Center part of the header
                    'content' => '',
                ],
                'R' => [
                    'content' => $RightFooterContent,
                ],
                'line' => 0, // That's the relevant parameter
            ];
        $headerFooterConfiguration = [
            'odd' => $oddEvenConfiguration,
            'even' => $oddEvenConfiguration
        ];
        $pdf->options = [
            'title' => 'Report Title',
            'defaultheaderline' => 0,
            'defaultfooterline' => 0,
            'subject' => 'Report Subject'];
    
        $pdf->methods = [
            'SetHeader' => [$headers],
            'SetFooter' => [$headerFooterConfiguration],
        ];
    
        return $pdf->render();
    
}
    function getprDetails2($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `fais-procurement`.`tbl_purchase_request_details`
            INNER JOIN `fais`.`tbl_unit_type` 
            ON `tbl_purchase_request_details`.`unit_id` = `tbl_unit_type`.`unit_type_id`
            WHERE `purchase_request_id`=" . $id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }
}
