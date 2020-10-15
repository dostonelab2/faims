<?php

namespace frontend\modules\finance\controllers;

use Yii;

use common\models\finance\Dv;
use common\models\finance\Os;
use common\models\finance\Osdv;
use common\models\finance\OsdvSearch;
use common\models\finance\Request;
use common\models\procurement\Expenditureclass;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;

/**
 * OsdvController implements the CRUD actions for Osdv model.
 */
class OsdvController extends Controller
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
     * Lists all Osdv models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OsdvSearch();
        
        /*if(Yii::$app->user->can('access-finance-obligation'))
            $status_id = Request::STATUS_VALIDATED;
        if(Yii::$app->user->can('access-finance-obligate'))
            $status_id = Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE;
        if(Yii::$app->user->can('access-finance-disbursement'))
            $status_id = Request::STATUS_ALLOTTED;
        if(Yii::$app->user->can('access-finance-certifycashavailable'))
            $status_id =  Request::STATUS_CERTIFIED_FUNDS_AVAILABLE;*/
        
        $status_id = Request::STATUS_VALIDATED;
        
        //$status_id = Request::STATUS_VALIDATED;
        //$searchModel->status_id = $status_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if(Yii::$app->user->can('access-finance-obligation'))
            $numberOfRequests = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_VALIDATED])->count();
        
        if(Yii::$app->user->can('access-finance-disbursement'))
            $numberOfRequests = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->count();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numberOfRequests' => $numberOfRequests,
        ]);
    }
    
    /**
     * Lists all Osdv models.
     * @return mixed
     */
    public function actionReport()
    {
        $searchModel = new OsdvSearch();
        
        $status_id = Request::STATUS_CHARGED;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('_report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Osdv models.
     * @return mixed
     */
    public function actionApprovalindex()
    {
        $searchModel = new OsdvSearch();
        
        //Yii::$app->user->can('access-finance-validation');
        $status_id = Request::STATUS_CHARGED;
        $searchModel->status_id = $status_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $numberOfRequests = Request::find()->where('status_id =:status_id',[':status_id'=>$status_id])->count();
        
        return $this->render('approvalindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numberOfRequests' => $numberOfRequests,
        ]);
    }

    /**
     * Displays a single Osdv model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $attachmentsDataProvider = new ActiveDataProvider([
            'query' => $model->request->getAttachments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);

        $allotmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAllotments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);

        $accountTransactionsDataProvider = new ActiveDataProvider([
            'query' => $model->getAccounttransactions(),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'debitcreditflag' => SORT_ASC,
                    'tax_category_id' => SORT_DESC,
                ]
            ],
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('kv-detail-success', 'Request Updated!');
        }
        
        return $this->render('view', [
            'model' => $model,
            'attachmentsDataProvider' => $attachmentsDataProvider,
            'allotmentsDataProvider' => $allotmentsDataProvider,
            'accountTransactionsDataProvider' => $accountTransactionsDataProvider,
            'year' => date('Y', strtotime($model->request->request_date)),
        ]);
    }
    
    public function actionApprovalview($id)
    {
        $model = $this->findModel($id);
        
        $attachmentsDataProvider = new ActiveDataProvider([
            'query' => $model->request->getAttachments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);

        $allotmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAllotments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);

        $accountTransactionsDataProvider = new ActiveDataProvider([
            'query' => $model->getAccounttransactions(),
            'pagination' => false,
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('kv-detail-success', 'Request Updated!');
        }
        
        return $this->render('approvalview', [
            'model' => $model,
            'attachmentsDataProvider' => $attachmentsDataProvider,
            'allotmentsDataProvider' => $allotmentsDataProvider,
            'accountTransactionsDataProvider' => $accountTransactionsDataProvider,
            'year' => date('Y', strtotime($model->request->request_date)),
        ]);
    }

    /**
     * Creates a new Osdv model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $model = new Osdv();

        if(Yii::$app->user->can('access-finance-obligation'))
            $requests = ArrayHelper::map(Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_VALIDATED])->all(),'request_id','request_number');
        
        if(Yii::$app->user->can('access-finance-disbursement'))
            $requests = ArrayHelper::map(Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->all(),'request_id','request_number');
        
        date_default_timezone_set('Asia/Manila');
        $model->create_date = date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->identity->user_id;
            if($model->save(false)){
                if($model->type_id == 1){
                    /*$os = new Os();
                    $os->osdv_id = $model->osdv_id;
                    $os->request_id = $model->request_id;
                    $os->os_number = Os::generateOsNumber($model->expenditure_class_id, $model->create_date);
                    $os->os_date = date("Y-m-d", strtotime($model->create_date));
                    $os->save(false);*/
                }
                //$request = Request::findOne($model->request_id);
                //$request->status_id = Request::STATUS_ALLOTTED;
                //$request->save(false);
                
                $model->request->status_id = Yii::$app->user->can('access-finance-disbursement') ? Request::STATUS_FOR_DISBURSEMENT : Request::STATUS_ALLOTTED;
                $model->request->save(false);
                return $this->redirect(['view', 'id' => $model->osdv_id]);   
            }
                 
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'requests' => $requests,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'requests' => $requests,
            ]);
        }
    }

    /**
     * Updates an existing Osdv model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->osdv_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Osdv model.
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
     * Finds the Osdv model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Osdv the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Osdv::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetrequest()
    {
        $model = Request::findOne($_GET['id']);
                
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_requestdetails', ['model'=>$model]);
        }
        else{
            return $this->render('_requestdetails', ['model'=>$model]);
        }
    }
    
    public function actionApprove()
    {
        $model = $this->findModel($_GET['id']);
        
        if(Yii::$app->user->can('access-finance-approval')){
            if (Yii::$app->request->post()) {
                $model->status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT; //70
                
                if($model->save(false)){
                    
                    $model->request->status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT; //70;
                    $model->request->save(false);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Approved!');
                    return $this->redirect(['approvalindex']);
                }else{
                    Yii::$app->session->setFlash('warning', $model->getErrors());                 
                }
                
            }
            
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_approve', ['model' => $model]);
            } else {
                return $this->render('_approve', ['model' => $model]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
        
    public function actionObligate()
    {
        $model = $this->findModel($_GET['id']);
        
        if(Yii::$app->user->can('access-finance-obligate')){
            if (Yii::$app->request->post()) {
                $model->status_id = Request::STATUS_ALLOTTED; //55
                
                if($model->save(false)){
                    
                    $model->request->status_id = Request::STATUS_ALLOTTED; //55;
                    $model->request->save(false);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Obligated!');
                    return $this->redirect(['view', 'id' => $model->osdv_id]);
                }else{
                    Yii::$app->session->setFlash('warning', $model->getErrors());                 
                }
                
            }
            
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_obligate', ['model' => $model]);
            } else {
                return $this->render('_obligate', ['model' => $model]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    public function actionCertifycashavailable()
    {
        $model = $this->findModel($_GET['id']);
        
        $model->cashAvailable = 1;
        $model->osdv_attributes = '1';
        
        if(Yii::$app->user->can('access-finance-certifycashavailable')){
            if (Yii::$app->request->post()) {
                $model->status_id = Request::STATUS_CHARGED; //60

                if(isset($_POST['Osdv']['subjectToAda']))  
                    $model->osdv_attributes .= ',2';
                
                if(isset($_POST['Osdv']['supportingDocumentsComplete']))  
                    $model->osdv_attributes .= ',3';
                    
                if($model->save(false)){
                    
                    $model->request->status_id = Request::STATUS_CHARGED; //60;
                    $model->request->save(false);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Certified Cash Available!');
                    return $this->redirect(['view', 'id' => $model->osdv_id]);
                }else{
                    Yii::$app->session->setFlash('warning', $model->getErrors());                 
                }
                
            }
            
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_certifycashavailable', ['model' => $model]);
            } else {
                return $this->render('_certifycashavailable', ['model' => $model]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    public function actionGenerateosnumber()
    {
        $model = $this->findModel($_GET['id']);
        
        if($model->allotments){
            if(Yii::$app->user->can('access-finance-generateosnumber')){
                if (Yii::$app->request->post()) {
                    $model->status_id = Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE; //50

                    if($model->save(false)){

                        $model->request->status_id = $model->status_id; //50;
                        $model->request->save(false);

                        if($model->type_id == 1){
                            $os = new Os();
                            $os->osdv_id = $model->osdv_id;
                            $os->request_id = $model->request->request_id;
                            $os->os_number = Os::generateOsNumber($model->expenditure_class_id, date("Y-m-d H:i:s"));
                            $os->os_date = date("Y-m-d", strtotime($model->create_date));
                            $os->save(false);
                        }
                        
                        Yii::$app->session->setFlash('success', 'OS Number Successfully Generated!');
                        return $this->redirect(['view', 'id' => $model->osdv_id]);
                    }else{
                        Yii::$app->session->setFlash('warning', $model->getErrors());                 
                    }
                    
                }

                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_generateos', ['model' => $model]);
                } else {
                    return $this->render('_generateos', ['model' => $model]);
                }
            }else{
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_notallowed', ['model'=>$model]);   
                }
            }
        }else{
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_requireos', ['model'=>$model]);   
            }
        }
        
        
    }
    
    public function actionGeneratedvnumber()
    {
        $model = $this->findModel($_GET['id']);
        
        if($model->accounttransactions){
            if(Yii::$app->user->can('access-finance-generatedvnumber')){
                if (Yii::$app->request->post()) {
                    $model->status_id = Request::STATUS_CERTIFIED_FUNDS_AVAILABLE; //60

                    if($model->save(false)){

                        $model->request->status_id = $model->status_id; //60;
                        if($model->request->save(false)){
                        //if($model->type_id == 1){
                            $dv = new Dv();
                            $dv->osdv_id = $model->osdv_id;
                            $dv->request_id = $model->request->request_id;
                            $dv->obligation_type_id = $model->request->obligation_type_id;
                            $dv->dv_number = Dv::generateDvNumber($model->request->obligation_type_id, $model->expenditure_class_id, date("Y-m-d H:i:s"));
                            $dv->dv_date = date("Y-m-d", strtotime($model->create_date));
                            $dv->save(false);
                        }

                        Yii::$app->session->setFlash('success', 'DV Number Successfully Generated!');
                        return $this->redirect(['view', 'id' => $model->osdv_id]);
                    }else{
                        Yii::$app->session->setFlash('warning', $model->getErrors());                 
                    }
                }

                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_generatedv', ['model' => $model]);
                } else {
                    return $this->render('_generatedv', ['model' => $model]);
                }
            }else{
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_notallowed', ['model'=>$model]);   
                }
            }
        }else{
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_requiredv', ['model'=>$model]);   
            }
        }
    }
    
    public function actionNotallowed()
    {
        $model = $this->findModel($_GET['id']);
        
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
    }
}
