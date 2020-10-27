<?php

namespace frontend\modules\cashier\controllers;

use Yii;
use common\models\cashier\Adanumber;
use common\models\cashier\Checknumber;
use common\models\cashier\Creditor;
use common\models\cashier\Lddapada;
use common\models\cashier\Lddapadaitem;
use common\models\cashier\LddapadaSearch;
use common\models\finance\Osdv;
use common\models\procurement\Assignatory;
use frontend\modules\cashier\components\Report;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\helpers\Json;

use kartik\mpdf\Pdf;
/**
 * LddapadaController implements the CRUD actions for Lddapada model.
 */
class LddapadaController extends Controller
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
     * Lists all Lddapada models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LddapadaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lddapada model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //$items = Lddapadaitem::find()->where(['lddapada_id' => $id])->all();
        $lddapadaItemsDataProvider = new ActiveDataProvider([
            'query' => $model->getLddapadaItems(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);
        
        return $this->render('view', [
            'model' => $model,
            //'items' => $items,
            'lddapadaItemsDataProvider' => $lddapadaItemsDataProvider,
            'id' => $id,
        ]);
    }

    /**
     * Creates a new Lddapada model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() 
    {
        $model = new Lddapada();
        $signatories = Assignatory::find()->where(['report_title' => 'LDDAP-ADA'])->one();
        $model->created_by = Yii::$app->user->identity->user_id;
        /*$listYear = ['2019' => '2019', '2018' => '2018'];
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');*/
        
        if ($model->load(Yii::$app->request->post())) {
            $model->batch_number = Lddapada::Batchnumber($_POST['Lddapada']['type_id']);
            
            date_default_timezone_set('Asia/Manila');
            $model->batch_date = date("Y-m-d H:i:s", strtotime("now"));
            $model->type_id = $_POST['Lddapada']['type_id'];
            if($model->save())
                return $this->redirect(['view', 'id' => (string) $model->lddapada_id]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['view', 'id' => $model->lddapada_id]);    
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'signatories' => $signatories,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'signatories' => $signatories,
            ]);
        }
    }

    /**
     * Updates an existing Lddapada model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->lddapada_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lddapada model.
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
     * Finds the Lddapada model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lddapada the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lddapada::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAdditems()
    {
        /** Post Data
            itemId : 4
            checked : true
            ppmpId : 2
            year : 2019
            
            creditorId:creditor_id,
            lddapadaId:lddapada_id,
            checked:checked},
        **/
        $lddapadaId = $_POST['lddapadaId'];
        $osdvId = $_POST['osdvId'];
        $checked = $_POST['checked'];
        
        //$creditor = Creditor::findOne($creditorId);
        $osdv = Osdv::findOne($osdvId);
        $lddapada_item = Lddapadaitem::find()->where([
                                    'lddapada_id' => $lddapadaId, 
                                    'osdv_id' => $osdvId])->one();
        
        if($lddapada_item)
        {
            //echo Json::encode(['message'=>$ppmp_item]);
            if($checked == 'true'){
                $lddapada_item->lddapada_id = $lddapadaId;
                $lddapada_item->active = 1;
                $lddapada_item->save(false);
            }
            else{
                //$lddapada_item->lddapada_id = 0;
                $lddapada_item->active = 0;
                $lddapada_item->save(false);
            }
        }else{
            $model = new Lddapadaitem();
        
            //lddapada_item_id 	lddapada_id 	creditor_id 	creditor_type_id 	name 	bank_name 	account_number 	gross_amount 	alobs_id 	expenditure_object_id 	check_number 	active
            $model->lddapada_id = $lddapadaId;
            $model->osdv_id = $osdv->osdv_id;
            $model->creditor_id = $osdv->request->payee_id;
            $model->creditor_type_id = $osdv->request->creditor->creditor_type_id;
            $model->name = $osdv->request->creditor->name;
            $model->bank_name = $osdv->request->creditor->bank_name;
            $model->account_number = $osdv->request->creditor->account_number;
            $model->gross_amount = $osdv->request->amount;
            $model->expenditure_object_id = $osdv->getAccountID();
            $model->active = 1;
            $model->save(false);
        }
        
        echo Json::encode(['message'=>$lddapadaId]);
    }
    
    public function actionUpdatechecknumber() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Lddapadaitem'][$index][$attr];
           $model = Lddapadaitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
               //echo Yii::$app->session->setFlash('success', "Quantity updated.");
           else
               return false;
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }
    
    public function actionUpdatealobs() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Lddapadaitem'][$index][$attr];
           $model = Lddapadaitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
               //echo Yii::$app->session->setFlash('success', "Quantity updated.");
           else
               return false;
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }
    
    public function actionAssigncheck($id){
        $model = new Checknumber;
        
        $year = date("Y");
        $month = date("m");
        $model->check_number = Checknumber::getCheckNumber($_GET['typeId'],$year, $month);
        
        if(Yii::$app->user->can('access-cashiering')){
            if (Yii::$app->request->post()) {
                //$model->saved = Lddapada::SAVED ; //20
                
                $counter = Checknumber::find()->where(['type_id' => $_GET['typeId'], 'year' => $year, 'month' => $month])->orderBy(['check_number_id' => SORT_DESC])->one();

                $counter = (int)$counter->counter + 1;
                
                $model->type_id = $_GET['typeId'];	
                //$model->prefix = Checknumber::PREP;
                $model->year = $year;
                $model->month = $month;
                $model->counter = $counter;
                
                if($model->save(false)){
                    
                    $keys = explode(',', $_POST['Checknumber']['selected_keys']);
                    $items = Lddapadaitem::find()
                        //->where(['IN', 'lddapada_item_id', [1,2]])
                        ->where(['IN', 'lddapada_item_id', $keys])
                        ->all();
                    foreach($items as $item){
                        $item->check_number = $_POST['Checknumber']['check_number'];
                        $item->save(false);
                    }
                    
                    /*$index = $model->lddapada_id;
                    $scope = 'Lddapada';
                    $data = $model->batch_number.':'.$model-> 	batch_date .':'.$model->request_type_id.':'.$model->payee_id.':'.$model->particulars.':'.$model->amount.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms, 'Request for Obligation', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);*/
                    
                    Yii::$app->session->setFlash('success', 'Successfully Saved!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $_GET['id']]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_assigncheck', ['model'=>$model]);   
            }else {
                return $this->render('_assigncheck', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    public function actionAssignada($id){
        $model = new Adanumber;
        
        $year = date("Y");
        $month = date("m");
        $model->ada_number = Adanumber::getAdaNumber($_GET['typeId'],$year, $month);
        
        if(Yii::$app->user->can('access-cashiering')){
            if (Yii::$app->request->post()) {
                //$model->saved = Lddapada::SAVED ; //20
                
                $ada = Adanumber::find()->where(['type_id' => $_GET['typeId'], 'year' => $year, 'month' => $month])->orderBy(['ada_number_id' => SORT_DESC])->one();

                $counter = (int)$ada->counter + 1;
                
                $model->type_id = $_GET['typeId'];	
                $model->prefix = $ada->prefix;
                $model->year = $year;
                $model->month = $month;
                $model->counter = $counter;
                
                if($model->save(false)){
                    
                    $keys = explode(',', $_POST['Adanumber']['selected_keys']);
                    $items = Lddapadaitem::find()
                        //->where(['IN', 'lddapada_item_id', [1,2]])
                        ->where(['IN', 'lddapada_item_id', $keys])
                        ->all();
                    foreach($items as $item){
                        $item->check_number = $_POST['Adanumber']['ada_number'];
                        $item->save(false);
                    }
                    
                    /*$index = $model->lddapada_id;
                    $scope = 'Lddapada';
                    $data = $model->batch_number.':'.$model-> 	batch_date .':'.$model->request_type_id.':'.$model->payee_id.':'.$model->particulars.':'.$model->amount.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms, 'Request for Obligation', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);*/
                    
                    Yii::$app->session->setFlash('success', 'Successfully Saved!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $_GET['id']]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_assignada', ['model'=>$model]);   
            }else {
                return $this->render('_assignada', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    function actionSave()
    {
        //$model = $this->findModel($_GET['id']);
        $model = new Lddapada();
        
        if(Yii::$app->user->can('access-cashiering')){
            if (Yii::$app->request->post()) {
                $model->saved = Lddapada::SAVED ; //20
                if($model->save(false)){
                    
                    /*$index = $model->lddapada_id;
                    $scope = 'Lddapada';
                    $data = $model->batch_number.':'.$model-> 	batch_date .':'.$model->request_type_id.':'.$model->payee_id.':'.$model->particulars.':'.$model->amount.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms, 'Request for Obligation', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);*/
                    
                    Yii::$app->session->setFlash('success', 'Successfully Saved!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $model->lddapada_id]);
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_save', ['model'=>$model]);   
            }else {
                return $this->render('_save', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    function actionPrint($id)
    {
        $report = new Report();
        $report->lddapada($id);
    }
    
    function actionPreview()
    {
        $url = 'http://localhost:8080/cashier/lddapada/print?id=2';
        //$url = 'D:/HP Files/2020/WFH/GCQ/W12/WFH-Report-August-7.pdf';
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_preview', ['url'=>$url]);   
        }else {
            return $this->render('_preview', ['url'=>$url]);
        }
    }
    
    function getItems()
    {
        $keys = $_POST['keys'];
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode([
                    'message' => 'success'
        ]);
    }
    
    public function actionBatchnumber()
    {
        $year = date("Y", strtotime("now"));
        $month = date("m", strtotime("now"));
        
        //$start_date = date("Y-m-d", strtotime($year.'-'.$month.'-1'));
        //$end_date = date("Y-m-t", strtotime($start_date));
        $number = Lddapada::find()->where(['type_id' => $_POST['typeId'], 'year(batch_date)' => date("Y", strtotime($year))])->orderBy(['lddapada_id' => SORT_DESC])->one();
        
        $batch = explode('-', $number->batch_number);
        //$count = Lddapada::find()->where(['between', 'batch_date', $start_date, $end_date])->count();
        $count = (int)$batch[2] + 1;
    
        return Lddapada::FUND_CODE.'-'.$month.'-'.str_pad($count, 3, '0', STR_PAD_LEFT).'-'.$year;
    }
}
