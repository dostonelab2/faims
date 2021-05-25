<?php

namespace frontend\modules\procurement\controllers;

use common\models\procurement\Purchaseorderdetails;
use Yii;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaseRequestDetails;
use common\models\procurement\Section;
use common\models\procurement\Tmpitem;
use frontend\models\PurchaserequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\db\Transaction;
use yii\db\Query;


//use yii\base\Exception;

/**
 * Purchaserequest2Controller implements the CRUD actions for Purchaserequest model.
 */
class Purchaserequest2Controller extends Controller
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
        //$prlist = new Query();
        $searchModel = new PurchaserequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchaserequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model =  PurchaseRequestDetails::find()->where(['purchase_request_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);
        return $this->renderAjax('view', [
            'model' => $model,
            'detail' => $this->findModel($id),
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Purchaserequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        //echo('<pre>');
        //var_dump(Json::encode($items));
        //echo('</pre>');
        $model = new Purchaserequest();

        $items = Tmpitem::find()->where(['session_id' => Yii::$app->session->getId()]);
        $itemDataProvider = new ActiveDataProvider([
            'query' => $items,
            'pagination' => false,
            'sort' => [
                'attributes' => ['item_id'],
            ],
        ]);
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['checkItem'])) {
                $tmpitem = Tmpitem::find()->where(['tmppritems_id' => $_POST['itemId']])->one();
                if ($tmpitem) {
                    //echo Json::encode(['message'=>$ppmp_item]);
                    if ($_POST['checked'] == 'true') {
                        $items->where(['checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                        $tmpitem->checked = 1;
                        $tmpitem->is_deleted = 0;
                        $tmpitem->save(false);
                        return $this->renderAjax('create', [
                            'model' => $model,
                            'itemDataProvider' => $itemDataProvider,
                        ]);
                    } else {
                        $items->where(['checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                        $tmpitem->checked = 0;
                        $tmpitem->save(false);
                        return $this->renderAjax('create', [
                            'model' => $model,
                            'itemDataProvider' => $itemDataProvider
                        ]);
                    }
                }
            } elseif (isset($_POST['selectSection']) == true) {
                $this->deleteTempItem();
                $this->setTempItem('section_id=' . '\'' . $_POST['section'] . '\' AND project_id=\'\'');
                $items->where(['section_id' => $_POST['section'], 'session_id' => Yii::$app->session->getId()]);
                //var_dump($items);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['selectProject']) == true) {
                $this->deleteTempItem();
                $this->setTempItem('project_id=' . '\'' . $_POST['project'] . '\' AND section_id=\'\'');
                $items->where(['project_id' => $_POST['project'], 'session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
                var_dump($items);
            } elseif (isset($_POST['reloadsectionitems']) == true) {
                //$this->setTempItem();
                $items->where(['section_id' => $_POST['section'], 'checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['reloadprojectitems']) == true) {
                //$this->setTempItem();
                $items->where(['project_id' => $_POST['project'], 'checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['removeitem']) == true) {
                //$this->setTempItem();
                $tmpitem = Tmpitem::find()->where(['tmppritems_id' => $_POST['tmppritems_id']])->one();
                $items->where(['checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                $tmpitem->checked = 0;
                $tmpitem->is_deleted = 1;
                $tmpitem->save(false);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['reloadremoveditems']) == true) {
                //$this->setTempItem();
                $items->where(['session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } else {
                $items->where(['section_id' => '', 'project_id' => '']);
                $this->deleteTempItem();
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider,
                    //'prNumber' => $this->GeneratePRid()
                ]);
            }
        } else {
            return $this->redirect('index');
        }
    }
    public function actionSubmitpr()
    {
        $session = Yii::$app->session;
        $purchase_request_id = $this->GeneratePRid();
        $purchase_request_number = $this->GeneratePRNumber();
        $session_id = Yii::$app->session->getId();
        $con = Yii::$app->procurementdb;
        $transaction = $con->beginTransaction();
        $sql = 'INSERT INTO `tbl_purchase_request_details`
                    (`purchase_request_id`,
                    `purchase_request_number`,
                    `unit_id`,
                    `purchase_request_details_item_description`,
                    `purchase_request_details_item_specification`,
                    `purchase_request_details_quantity`,
                    `purchase_request_details_price`) 
                SELECT :purchase_request_id AS `purchase_request_id`,
                       :purchase_request_number AS `purchase_request_number`,
                        `unit`,
                        `description`,
                        `specs`,
                        `qty`,
                        `cost`
                FROM `tmppritems` 
                WHERE session_id = :session_id AND checked = 1';
        try {
            if (Yii::$app->request->isPost) {
                $model = new Purchaserequest();
                $model->purchase_request_id = $purchase_request_id;
                $model->purchase_request_number = $purchase_request_number;

                if ($_POST['Purchaserequest']['purchase_request_sai_number'] != '') {
                    $model->purchase_request_sai_number = $_POST['Purchaserequest']['purchase_request_sai_number'];
                    $model->purchase_request_saidate = $_POST['Purchaserequest']['purchase_request_saidate'];
                }

                $section = Section::findOne($_POST['Purchaserequest']['hidden_section_id']);
                $model->section_id = $_POST['Purchaserequest']['hidden_section_id'];
                $model->division_id = $section->division_id;

                if (isset($_POST['Purchaserequest']['project_id'])) $model->project_id = $_POST['Purchaserequest']['project_id'];
                $model->purchase_request_date = $_POST['Purchaserequest']['purchase_request_date'];
                $model->purchase_request_purpose = $_POST['Purchaserequest']['purchase_request_purpose'];
                if ($_POST['Purchaserequest']['purchase_request_referrence_no'] != '') $model->purchase_request_referrence_no = $_POST['Purchaserequest']['purchase_request_referrence_no'];
                if ($_POST['Purchaserequest']['purchase_request_project_name'] != '') $model->purchase_request_project_name = $_POST['Purchaserequest']['purchase_request_project_name'];
                if ($_POST['Purchaserequest']['purchase_request_location_project'] != '') $model->purchase_request_location_project = $_POST['Purchaserequest']['purchase_request_location_project'];
                //$model->section_id = $_POST['Purchaserequest']['hidden_section_id'];
                $model->purchase_request_requestedby_id = $_POST['Purchaserequest']['purchase_request_requestedby_id'];
                $model->purchase_request_approvedby_id = $_POST['Purchaserequest']['purchase_request_approvedby_id'];
                $model->user_id = Yii::$app->user->id;
                if ($model->save(false)) {
                    $con->createCommand($sql)
                        ->bindValues([
                            ':purchase_request_id' => $purchase_request_id,
                            ':purchase_request_number' => $purchase_request_number,
                            ':session_id' => $session_id
                        ])
                        ->execute();
                }
                if (!$model->save(false)) throw new \Exception('Not Save...');
                $transaction->commit();
                $session->set('savepopup', "executed");
                return $this->redirect('index');
            }
            $session->set('errorpopup', "executed");
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
    }

    /**
     * Updates an existing Purchaserequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);
        $prdetails = PurchaseRequestDetails::find()->where(['purchase_request_id' => $model->purchase_request_id])->all();
        
        $session_id = Yii::$app->session->getId();
        //if (Yii::$app->request->isAjax) {
        $timestamp = strtotime($model->purchase_request_date);

        if (Yii::$app->request->isAjax) {
            $this->deleteTempItem();
            $con = Yii::$app->procurementdb;
            $sql =
                'INSERT INTO `tmppritems`(`session_id` ,`ppmp_id`, `division_id`, 
                    `section_id`, `project_id`,
                    `year`, `item_id`,
                    `description`, `unit`,
                    `unit_description`, `cost`,
                    `checked`, `qty`,
                    `approved_qty`, `supplemental`, 
                    `status_id`,
                    `date`) 
                SELECT \'' . $session_id . '\' AS session_id,
                    `ppmp_id`, `division_id`, 
                    `section_id`, `project_id`,
                    `year`, `item_id`,
                    `description`, `unit`,
                    `unit_description`, `cost`,
                    `checked`, `qty`,
                    `approved_qty`, `supplemental`, 
                    `status_id`,
                    DATE(NOW()) AS DATE
                FROM vw_ppmp_items WHERE section_id = :section_id 
                    AND project_id = :project_id                   
                    AND year = :year AND status_id=3
                    AND NOT EXISTS (SELECT session_id FROM tmppritems
                                    WHERE session_id = :session_id)';
            $con->createCommand($sql)
                ->bindValues([
                    ':section_id' => $model->project_id == '' ? $model->section_id : '',
                    ':project_id' => $model->project_id == '' ? '' : $model->project_id,
                    ':session_id' => $session_id,
                    ':year' => date('Y', $timestamp)
                ])
                ->execute();
            $tmpitems = Tmpitem::find();

            foreach ($prdetails as $prdetail) {
                $item = $tmpitems->where(['session_id' => Yii::$app->session->getId(), 'description' => $prdetail->purchase_request_details_item_description])->one();
                $item->checked = 1;
                $item->specs = $prdetail->purchase_request_details_item_specification;
                $item->qty = $prdetail->purchase_request_details_quantity;
                $item->save(false);
            }
            $items = Tmpitem::find()->where(['session_id' => Yii::$app->session->getId(), 'checked' => 1]);
            $items2 = $tmpitems->where(['session_id' => Yii::$app->session->getId()]);
            $itemDataProvider = new ActiveDataProvider([
                'query' => $items,
                'pagination' => false,
                'sort' => [
                    'attributes' => ['item_id'],
                ],
            ]);
            $itemDataProvider2 = new ActiveDataProvider([
                'query' => $items2,
                'pagination' => false,
                'sort' => [
                    'attributes' => ['item_id'],
                ],
            ]);
            return $this->renderAjax('update', [
                'model' => $model,
                'itemDataProvider' => $itemDataProvider,
                'itemDataProvider2' => $itemDataProvider2
            ]);
        } 
        
        if (Yii::$app->request->post()) {
            $items = Tmpitem::find()->where(['session_id' => Yii::$app->session->getId(), 'checked' => 1])->all();
            $model->purchase_request_purpose = $_POST['Purchaserequest']['purchase_request_purpose'];
            $model->purchase_request_referrence_no = $_POST['Purchaserequest']['purchase_request_referrence_no'];
            $model->purchase_request_project_name = $_POST['Purchaserequest']['purchase_request_project_name'];
            $model->purchase_request_location_project = $_POST['Purchaserequest']['purchase_request_location_project'];
            if ($model->save()) {
                $deleteditems = Tmpitem::find()->where(['session_id' => Yii::$app->session->getId(), 'is_deleted' => 1])->all();
                foreach($deleteditems as $deleteditem){
                    $isdeleteditem = PurchaseRequestDetails::find()->where(['purchase_request_id' => $model->purchase_request_id, 'purchase_request_details_item_description' => $deleteditem['description']])->one();
                    if($isdeleteditem){
                        $isdeleteditem->delete();
                    }
                }
                foreach($items as $item){
                    $pritems = PurchaseRequestDetails::find()->where(['purchase_request_id' => $model->purchase_request_id, 'purchase_request_details_item_description' => $item['description']])->one();
                    if($pritems){
                        $pritems->purchase_request_details_quantity = $item['qty'];
                        $pritems->purchase_request_details_item_specification = $item['specs'];
                        $pritems->save(false);
                    }
                    if(!$pritems){
                        $pritems2 = new PurchaseRequestDetails();
                        $pritems2->purchase_request_id = $id;
                        $pritems2->purchase_request_number = $model->purchase_request_number;
                        $pritems2->purchase_request_details_unit = $item['unit_description'];
                        $pritems2->unit_id = $item['unit'];
                        $pritems2->purchase_request_details_item_description = $item['description'];
                        $pritems2->purchase_request_details_item_specification = $item['specs'];
                        $pritems2->purchase_request_details_quantity = $item['qty'];
                        $pritems2->purchase_request_details_price = $item['cost'];
                        $pritems2->save(false);
                    }        
                    //echo $item['qty'];
                }
                $session->set('savepopup', "executed");
                return $this->redirect('index');
            }
        }
        return $this->redirect('index');
    }

    /**
     * Deletes an existing Purchaserequest model.
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
    public function setTempItem($selection)
    {
        $session_id = '\'' . Yii::$app->session->getId() . '\'';
        //if (Yii::$app->request->isAjax) {
        $con = Yii::$app->procurementdb;
        $sql =
            'INSERT INTO `tmppritems`(`session_id` ,`ppmp_id`, `division_id`, 
                    `section_id`, `project_id`,
                    `year`, `item_id`,
                    `description`, `unit`,
                    `unit_description`, `cost`,
                    `checked`, `qty`,
                    `approved_qty`, `supplemental`, 
                    `status_id`,
                    `date`) 
                SELECT ' . $session_id . ' AS session_id,
                    `ppmp_id`, `division_id`, 
                    `section_id`, `project_id`,
                    `year`, `item_id`,
                    `description`, `unit`,
                    `unit_description`, `cost`,
                    `checked`, `qty`,
                    `approved_qty`, `supplemental`, 
                    `status_id`,
                    DATE(NOW()) as date
                FROM vw_ppmp_items WHERE ' . $selection .
            ' AND year=' . $_POST['year'] . ' AND status_id=3
                    AND NOT EXISTS (SELECT session_id FROM tmppritems
                                    WHERE session_id = ' . $session_id . ')';
        $con->createCommand($sql)->execute();
        //}
    }
    public function deleteTempItem()
    {
        $con = Yii::$app->procurementdb;
        $sql = 'DELETE FROM tmppritems WHERE session_id=' . '\'' . Yii::$app->session->getId() . '\' OR date < DATE(NOW())';
        $con->createCommand($sql)->execute();
    }
    public function actionUpdateqty()
    {
        if (Yii::$app->request->isAjax) {
            $tmpitem = Tmpitem::find()->where(['tmppritems_id' => $_POST['item_id']])->one();
            $tmpitem->qty = $_POST['qty'];
            $tmpitem->save(false);
        }
    }
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
    public function GeneratePRid()
    {
        $con = Yii::$app->procurementdb;
        $command = $con->createCommand("SELECT MAX(purchase_request_id) + 1 AS NextNumber  FROM tbl_purchase_request");
        $nextValue = $command->queryOne();
        return $nextValue['NextNumber'];
    }
    public function loadspecs($id){
        $model =  Tmpitem::find()->where(['tmppritems_id' => $id]);
    }
    public function actionUpdatespecs(){
        $id = $_POST['item_id'];
        $model = Tmpitem::find()->where(['tmppritems_id' => $id])->one();
        $model->specs = $_POST['specs'];
        $model->save(false);
    }
}
