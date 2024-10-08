<?php

namespace frontend\modules\finance\controllers;

use Yii;

use common\models\cashier\CreditorSearch;
use common\models\cashier\LddapadaitemSearch;
use common\models\finance\Dv;
use common\models\finance\Os;
use common\models\finance\Osdv;
use common\models\finance\AccounttransactionSearch;
use common\models\finance\CheckdisbursementjournalSearch;
use common\models\finance\ObligationSearch;
use common\models\finance\Obligationtype;
use common\models\finance\OsallotmentSearch;
use common\models\finance\OsdvSearch;
use common\models\finance\OsdvapprovalSearch;
use common\models\finance\Request;
use common\models\finance\Requestpayroll;
use common\models\finance\RequestpayrollSearch;
use common\models\finance\RequestSearch;
use common\models\finance\RequestosdvSearch;
use common\models\finance\OsdvreportSearch;
use common\models\procurement\Expenditureclass;
use common\models\sec\Blockchain;

use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\helpers\Html;

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
    public function actionIndex2()
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
        
        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numberOfRequests' => $numberOfRequests,
        ]);
    }
    
    public function actionIndex()
    {
        $searchModel = new RequestosdvSearch();
        //$searchModel->status_id = Request::STATUS_VALIDATED;
        
        //if(Yii::$app->user->identity->username != 'Admin')
            //$searchModel->created_by =  Yii::$app->user->identity->user_id;
        if(isset($_GET['year']))
            $searchModel->year = $_GET['year'];
        else
            $searchModel->year = date('Y');
            
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if(Yii::$app->user->can('access-finance-obligation'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_VALIDATED])->count();
        
        if(Yii::$app->user->can('access-finance-disbursement'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->count();
        else    
            $numberOfRequests = 0;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numberOfRequests' => $numberOfRequests,
        ]);
    }

    public function actionObligationindex()
    {
        $searchModel = new ObligationSearch();
        $searchModel->status_ids = [Request::STATUS_VALIDATED, Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //if(Yii::$app->user->can('access-finance-obligation'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_VALIDATED])->count();
        
        // if(Yii::$app->user->can('access-finance-disbursement'))
        //     $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->count();
        // else    
        //     $numberOfRequests = 0;
        
        return $this->render('obligationindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numberOfRequests' => $numberOfRequests,
        ]);
    }

    public function actionObligatedindex()
    {
        $searchModel = new ObligationSearch();
        $searchModel->status_ids = [Request::STATUS_ALLOTTED];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*if(Yii::$app->user->can('access-finance-obligation'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_VALIDATED])->count();
        
        if(Yii::$app->user->can('access-finance-disbursement'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->count();
        else    
            $numberOfRequests = 0;*/
        
        return $this->render('obligatedindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // 'numberOfRequests' => $numberOfRequests,
        ]);
    }

    public function actionDisbursementindex()
    {
        $searchModel = new ObligationSearch();
        $searchModel->status_ids = [Request::STATUS_ALLOTTED, Request::STATUS_CERTIFIED_FUNDS_AVAILABLE];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // if(Yii::$app->user->can('access-finance-obligation'))
            // $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_VALIDATED])->count();
        
        // if(Yii::$app->user->can('access-finance-disbursement'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->count();
        // else    
        //     $numberOfRequests = 0;
        
        return $this->render('disbursementindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numberOfRequests' => $numberOfRequests,
        ]);
    }

    public function actionDisbursedindex()
    {
        $searchModel = new ObligationSearch();
        $searchModel->status_ids = [Request::STATUS_CHARGED];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*if(Yii::$app->user->can('access-finance-obligation'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_VALIDATED])->count();
        
        if(Yii::$app->user->can('access-finance-disbursement'))
            $numberOfRequests = Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->count();
        
        else    
            $numberOfRequests = 0;*/
        
        return $this->render('disbursedindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // 'numberOfRequests' => $numberOfRequests,
        ]);
    }
    
    public function actionCoaindex()
    {
        $searchModel = new RequestosdvSearch();
        $searchModel->status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('coaindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Osdv models.
     * @return mixed
     */
    public function actionReport()
    {
        $searchModel = new OsdvreportSearch();
        
        $status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT;
        
        if(isset($_GET['OsdvreportSearch'])){
            
            $request_date_s = date('Y-m-d', strtotime("-1 day", strtotime($_GET['OsdvreportSearch']['request_date_s'])));
            $request_date_e = date('Y-m-d', strtotime("+1 day", strtotime($_GET['OsdvreportSearch']['request_date_e'])));
            
            $searchModel->request_date_s = $request_date_s;
            $searchModel->request_date_e = $request_date_e;    
        }
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('_report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionReportpayroll()
    {
        $searchModel = new RequestpayrollSearch();
        
        $status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT;
        
        /*if(isset($_GET['RequestpayrollSearch'])){
            
            $request_date_s = date('Y-m-d', strtotime("-1 day", strtotime($_GET['RequestpayrollSearch']['request_date_s'])));
            $request_date_e = date('Y-m-d', strtotime("+1 day", strtotime($_GET['RequestpayrollSearch']['request_date_e'])));
            
            $searchModel->request_date_s = $request_date_s;
            $searchModel->request_date_e = $request_date_e;    
        })*/
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('_reportpayroll', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionObligationreport()
    {
        $searchModel = new OsallotmentSearch();
        
        // if(!isset($_GET['OsallotmentSearch'])){
        if(!isset($_GET['OsallotmentSearch']['dv_date_s']) && !isset($_GET['OsallotmentSearch']['dv_date_s'])){
            $dv_date_s = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-01'))));
            $dv_date_e = date('Y-m-d', strtotime("+1 day", strtotime(date('Y-m-t'))));
        }else{
            $dv_date_s = date('Y-m-d', strtotime("-1 day", strtotime($_GET['OsallotmentSearch']['dv_date_s'])));
            $dv_date_e = date('Y-m-d', strtotime("+1 day", strtotime($_GET['OsallotmentSearch']['dv_date_e'])));
        }
        $searchModel->dv_date_s = $dv_date_s;
        $searchModel->dv_date_e = $dv_date_e;
        //if(isset($_GET['obligation_type_id']))
            //$searchModel->obligation_type_id = $_GET['obligation_type_id'];  
            
        // $searchModel->obligation_type_id = 1; 
        $toolbars = '';
        $obligation_types = Obligationtype::find()->all();
        // Blockchain::find()->where(['index_id' => $id, 'scope' => 'Request'])->all();

        foreach($obligation_types as $obligation_type){
            $toolbars .= Html::a($obligation_type->name, ['obligationreport?OsallotmentSearch[dv_date]='.$dv_date_s.' - '.$dv_date_e.'&OsallotmentSearch[obligation_type_id]='.$obligation_type->type_id], 
                            [   
                                'class' => 'btn btn-success',
                                'data-pjax' => 0,
                            ]
                        ).' ';

            /*$toolbars .= Html::a($obligation_type->name, [
                    'obligationreport?OsallotmentSearch[dv_date]='.$dv_date_s.' - '.$dv_date_e.'&OsallotmentSearch[unit_id]='.$obligation_type->type_id], [
                'class' => 'btn btn-outline-secondary',
                'style' => 'color: B76E79; font-weight: bold;',
                'data-pjax' => 0, 
            ]);*/
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('_obligationreport', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'toolbars' => $toolbars,
        ]);
    }
    
    public function actionCheckdisbursementjournal()
    {
        //$searchModel = new LddapadaitemSearch();
        $searchModel = new CheckdisbursementjournalSearch();
        $searchModel->active = 1;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('_checkdisbursementjournal2', [
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
        $searchModel = new OsdvapprovalSearch();
        
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
    public function actionCoaview($id)
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
        
        $payrollDataprovider = new ActiveDataProvider([
            'query' => $model->getPayrollitems(),
            'pagination' => false,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            
            if($model->save()){ 
                $model->request->amount = $_POST['Osdv']['grossamount'];
                $model->request->save();
                Yii::$app->session->setFlash('kv-detail-success', 'Request Updated!');
            }
            
        }

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
            Yii::$app->session->setFlash('kv-detail-success', 'Obligation Updated!');
        }
        
        return $this->render('coaview', [
            'model' => $model,
            'payrollDataprovider' => $payrollDataprovider,
            'attachmentsDataProvider' => $attachmentsDataProvider,
            'allotmentsDataProvider' => $allotmentsDataProvider,
            'accountTransactionsDataProvider' => $accountTransactionsDataProvider,
            'year' => date('Y', strtotime($model->request->request_date)),
        ]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $_obligationType = $model->type_id;
        $attachmentsDataProvider = new ActiveDataProvider([
            'query' => $model->request->getAttachments(),
            'pagination' => false,
        ]);

        $allotmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAllotments(),
            'pagination' => false,
        ]);
        
        $payrollDataprovider = new ActiveDataProvider([
            'query' => $model->getPayrollitems(),
            'pagination' => false,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            
            if($model->save()){ 
                $model->request->amount = $_POST['Osdv']['grossamount'];
                $model->request->obligation_type_id = $_POST['Osdv']['type_id'];
                $model->request->save();

                /* Bug #001 : Error when printing DV */
                if($_obligationType != $_POST['Osdv']['type_id']){
                    $chain = Blockchain::find()
                        ->where(['index_id' => $model->request_id, 'scope' => 'Request'])
                        ->orderBy(['blockchain_id' => SORT_DESC])
                        ->one();
                    if($_POST['Osdv']['type_id'] == 1)
                        $status = '40';
                    else
                        $status = '58';
                    $chain->data = substr($chain->data, 0, -2).$status;
                    $chain->save();
                }
                /* End */

                Yii::$app->session->setFlash('kv-detail-success', 'Request Updated!');
            }
            
        }

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
        
        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //    Yii::$app->session->setFlash('kv-detail-success', 'Obligation Updated!');
        //}
        
        return $this->render('view', [
            'model' => $model,
            'payrollDataprovider' => $payrollDataprovider,
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
        ]);

        $allotmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAllotments(),
            'pagination' => false,
        ]);
        
        $payrollDataprovider = new ActiveDataProvider([
            'query' => $model->getPayrollitems(),
            'pagination' => false,
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
            'payrollDataprovider' => $payrollDataprovider,
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
            $requests = ArrayHelper::map(Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_VALIDATED])->all(),'request_id','request_number');
        
        if(Yii::$app->user->can('access-finance-disbursement'))
            $requests = ArrayHelper::map(Request::find()->where('status_id =:status_id AND cancelled = 0',[':status_id'=>Request::STATUS_FOR_DISBURSEMENT])->all(),'request_id','request_number');
        
        if( (Yii::$app->user->identity->username == 'Admin') )
            $requests = ArrayHelper::map(
                Request::find()
                    ->where(['status_id' => Request::STATUS_VALIDATED])
                    ->andWhere(['status_id' => Request::STATUS_FOR_DISBURSEMENT])
                    ->andWhere(['cancelled' => 0])
                    ->all(),'request_id','request_number');
        
        date_default_timezone_set('Asia/Manila');
        $model->create_date = date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->identity->user_id;
            // $model->status_id = ($model->type_id == 1) ? Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE : Request::STATUS_FOR_DISBURSEMENT;
            $model->status_id = ($model->type_id == 1) ? Request::STATUS_FOR_ALLOTMENT : Request::STATUS_FOR_DISBURSEMENT;
            $model->remarks = '';
            $model->payroll = $_POST['Osdv']['payroll'];
            if($model->save(false)){
                // if($model->type_id == 1){
                    /*$os = new Os();
                    $os->osdv_id = $model->osdv_id;
                    $os->request_id = $model->request_id;
                    $os->os_number = Os::generateOsNumber($model->expenditure_class_id, $model->create_date);
                    $os->os_date = date("Y-m-d", strtotime($model->create_date));
                    $os->save(false);*/
                // }
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

    public function actionPayrollitems()
    {
        $id = $_GET['id'];
        $model = new Requestpayroll();
        
        $model->osdv_id = $id;
        //creditor_type_id
        /* 
        Payroll Regular(13), 
        Payroll COntractual(14), 
        MC Benefits(15), 
        Hazard Contractual(16), 
        Cash Award / Special Award(33)
        */
        if ($model->load(Yii::$app->request->post())) {
            
            $model->creditor_id = $_POST['Requestpayroll']['creditor_id'];
            $model->particulars = $_POST['Requestpayroll']['particulars'];
            $model->status_id = 0;
            $model->active = 1;
            if($model->save(false)){
                return $this->redirect(['view', 'id' => $model->osdv_id]);   
            }
                 
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_formcreditor', [
                        'model' => $model,
                        'osdv_id' => $id,
            ]);
        } else {
            return $this->render('_formcreditor', [
                        'model' => $model,
                        'osdv_id' => $id,
            ]);
        }
    }
    
    public function actionPayrollitems2()
    {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        
        $searchModel = new CreditorSearch();
        
        //creditor_type_id
        /* 
        Payroll Regular(13), 
        Payroll COntractual(14), 
        MC Benefits(15), 
        Hazard Contractual(16), 
        Cash Award / Special Award(33)
        */
        if($model->request->request_type_id == 13 || $model->request->request_type_id == 15){
            $searchModel->creditor_type_id = 5;
        }elseif($model->request->request_type_id == 14 || $model->request->request_type_id == 16){
            $searchModel->creditor_type_id = 5;
        }elseif($model->request->request_type_id == 33){
            $searchModel->creditor_type_id = [1,2];
        }elseif($model->request->request_type_id == 34){
            $searchModel->creditor_type_id = 5;
        }        
        
        $searchModel->payroll = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_payrollitems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
            ]);
        } else {
            return $this->render('_payrollitems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
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
                    
                    $index = $model->osdv_id;
                    $scope = 'Osdv';
                    $data = $model->osdv_id.':'.$model->request_id.':'.$model->type_id.':'.$model->expenditure_class_id.':'.$model->osdv_attributes.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
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
    
    public function actionApprovepayroll()
    {
        //$model = $this->findModel($_GET['id']);
        $model = Requestpayroll::findOne($_GET['id']);
        
        if(Yii::$app->user->can('access-finance-approval')){
            if (Yii::$app->request->post()) {
                $status = $this->allApproved($model->osdv) ? Request::STATUS_APPROVED_FOR_DISBURSEMENT : Request::STATUS_APPROVED_PARTIAL; //70 or 67
                $model->status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT;
                
                if($model->save(false)){
                    
                    $model->osdv->status_id = $status;
                    $model->osdv->save(false);
                    
                    $model->osdv->request->status_id = $status;
                    $model->osdv->request->save(false);
                    
                    $index = $model->osdv_id;
                    $scope = 'Osdv';
                    $data = $model->osdv_id.':'.$model->osdv->request_id.':'.$model->osdv->type_id.':'.$model->osdv->expenditure_class_id.':'.$model->osdv_attributes.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Approved!');
                    return $this->redirect(['approvalview', 'id' => $model->osdv_id]);
                    //return $this->redirect(['approvalindex']);
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
                    
                    $index = $model->osdv_id;
                    $scope = 'Osdv';
                    $data = $model->osdv_id.':'.$model->request_id.':'.$model->type_id.':'.$model->expenditure_class_id.':'.$model->osdv_attributes.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
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
    
    public function actionReassign()
    {
        $modelOsdv = $this->findModel($_GET['id']);
        $model = new Os();
        
        if(Yii::$app->user->can('access-finance-generateosnumber')){
            if (Yii::$app->request->post()) {
                $old_OS = $modelOsdv->os->os_number;
                $modelOsdv->os->deleted = 1;
                
                if($modelOsdv->os->save(false)){
                    
                    if($modelOsdv->type_id == 1){
                            $os = new Os();
                            $os->osdv_id = $_POST['Os']['_osdv_id'];
                            $os->request_id = $_POST['Os']['_request_id'];
                            $os->os_number = $_POST['Os']['os_number']; //Os::generateOsNumber($_model->expenditure_class_id, date("Y-m-d H:i:s"));
                            $os->os_date = date("Y-m-d", strtotime($_POST['Os']['os_date']));
                            //$os->os_date = date("Y-m-d H:i:s");
                            $os->save(false);
                        }
                    
                    $index = $modelOsdv->osdv_id;
                    $scope = 'Osdv';
                    $data = $modelOsdv->osdv_id.':'.$modelOsdv->request_id.': OS number - '.$old_OS.' to '.$os->os_number.'.';
                    Blockchain::createBlock($index, $scope, $data);
                    
                    Yii::$app->session->setFlash('success', 'Obligation Successfully Reassigned!');
                    return $this->redirect(['view', 'id' => $modelOsdv->osdv_id]);
                }else{
                    Yii::$app->session->setFlash('warning', $model->getErrors());                 
                }
                
            }
            
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_reassign', ['model' => $model, 'modelOsdv' => $modelOsdv]);
            } else {
                return $this->render('_reassign', ['model' => $model, 'modelOsdv' => $modelOsdv]);
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
                $model->status_id = Request::STATUS_CHARGED; //65

                if(isset($_POST['Osdv']['subjectToAda']))  
                    $model->osdv_attributes .= ',2';
                
                if(isset($_POST['Osdv']['supportingDocumentsComplete']))  
                    $model->osdv_attributes .= ',3';
                    
                if($model->save(false)){
                    
                    $model->request->status_id = Request::STATUS_CHARGED; //65;
                    
                    if($model->request->save(false)){
                        if($model->request->payroll){
                            $payroll = Requestpayroll::findOne($_GET['request_payroll_id']);
                            $payroll->status_id = Request::STATUS_CHARGED;
                            $payroll->osdv_attributes = $model->osdv_attributes;
                            $payroll->save(false);
                        }
                        
                        $index = $model->osdv_id;
                        $scope = 'Osdv';
                        $data = $model->osdv_id.':'.$model->request_id.':'.$model->type_id.':'.$model->expenditure_class_id.':'.$model->osdv_attributes.':'.$model->status_id;
                        Blockchain::createBlock($index, $scope, $data);
                    }
                    
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
                            //$os->os_date = date("Y-m-d", strtotime($model->create_date));
                            $os->os_date = date("Y-m-d H:i:s");
                            // $os->os_date = "2023-06-01";
                            $os->save(false);
                        }
                        
                        $index = $model->osdv_id;
                        $scope = 'Osdv';
                        $data = $model->osdv_id.':'.$model->request_id.':'.$model->type_id.':'.$model->expenditure_class_id.':'.$os->os_number.':'.$model->osdv_attributes.':'.$model->status_id;
                        Blockchain::createBlock($index, $scope, $data);
                        
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

                    if($model->payroll){
                        if($model->save(false)){
                            $model->request->status_id = $model->status_id; //60;
                            //foreach($model->payrollitems as $payroll){
                                if($model->request->save(false)){
                                    $dv = new Dv();
                                    $dv->osdv_id = $model->osdv_id;
                                    $dv->request_id = $model->request->request_id;
                                    $dv->obligation_type_id = $model->request->obligation_type_id;
                                    $dv->dv_number = Dv::generateDvNumber($model->request->obligation_type_id, $model->expenditure_class_id, date("Y-m-d H:i:s"));
                                    $dv->dv_date = date("Y-m-d H:i:s");
                                    // $dv->dv_date = "2023-06-01";
                                    if($dv->save(false)){
                                        $payroll = Requestpayroll::findOne($_GET['request_payroll_id']);
                                        $payroll->dv_id = $dv->dv_id;
                                        $payroll->status_id = Request::STATUS_CERTIFIED_FUNDS_AVAILABLE;
                                        $payroll->save(false);
                                    }
                                }
                            //}
                            Yii::$app->session->setFlash('success', 'DVs Number Successfully Generated!');
                            return $this->redirect(['view', 'id' => $model->osdv_id]);
                        }
                    }else{
                        if($model->save(false)){
                            $model->request->status_id = $model->status_id; //60;
                            if($model->request->save(false)){
                            //if($model->type_id == 1){
                                $dv = new Dv();
                                $dv->osdv_id = $model->osdv_id;
                                $dv->request_id = $model->request->request_id;
                                $dv->obligation_type_id = $model->request->obligation_type_id;
                                $dv->dv_number = Dv::generateDvNumber($model->request->obligation_type_id, $model->expenditure_class_id, date("Y-m-d H:i:s"));
                                //$dv->dv_date = date("Y-m-d", strtotime($model->create_date));
                                $dv->dv_date = date("Y-m-d H:i:s");
                                $dv->save(false);
                            }

                            $index = $model->osdv_id;
                            $scope = 'Osdv';
                            $data = $model->osdv_id.':'.$model->request_id.':'.$model->type_id.':'.$model->expenditure_class_id.':'.$dv->dv_number.':'.$model->osdv_attributes.':'.$model->status_id;
                            Blockchain::createBlock($index, $scope, $data);

                            Yii::$app->session->setFlash('success', 'DV Number Successfully Generated!');
                            return $this->redirect(['view', 'id' => $model->osdv_id]);
                        }else{
                            Yii::$app->session->setFlash('warning', $model->getErrors());                 
                        }
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
    
    public function allApproved($model)
    {
        $status = false;
        foreach($model->payrollitems as $item)
        {   
            $status = ($item->status_id == 70) ? true : false;
            if(!$status)
            break;
        }
        echo $status;
    }
}