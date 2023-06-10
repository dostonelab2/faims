<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\apiservice\Notificationrecipient;
use frontend\modules\finance\components\Report;

use common\models\cashier\Creditortmp;
use common\models\cashier\CreditorSearch;
use common\models\finance\Project;
use common\models\finance\Projecttype;
use common\models\finance\Request;
use common\models\finance\Requestdistrict;
use common\models\finance\Requestattachment;
use common\models\finance\Requestattachmentsigned;
use common\models\finance\Requestpayroll;
use common\models\finance\Requesttype;
use common\models\finance\RequestSearch;
use common\models\procurement\Disbursement;
use common\models\procurement\Divisionhead;
use common\models\sec\Blockchain;
use common\models\system\Comment;
use common\models\system\CommentSearch;
use common\models\system\User;

use kartik\mpdf\Pdf;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
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
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex2()
    {
        $searchModel = new RequestSearch();
        if(Yii::$app->user->identity->username != 'Admin')
            $searchModel->created_by =  Yii::$app->user->identity->user_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            
        ]);
    }
    
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestSearch();
        if(Yii::$app->user->identity->username != 'Admin')
            $searchModel->created_by =  Yii::$app->user->identity->user_id;
        //$searchModel->status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT;
        
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            
        ]);
    }
    
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionApprovedindex()
    {
        $searchModel = new RequestSearch();
        //if(Yii::$app->user->identity->username != 'Admin')
            //$searchModel->created_by =  Yii::$app->user->identity->user_id;
        $searchModel->status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('approvedindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            
        ]);
    }
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionVerifyindex()
    {
        $searchModel = new RequestSearch();
        $searchModel->status_id = Request::STATUS_SUBMITTED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $CurrentUser= User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
        
        return $this->render('verifyindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionValidateindex()
    {
        /** UserIDs : ** MAW=2 , RSS=4 , MLK=3 , GFP=62 , NMA=70 , JAP=54 , RJA=55 **/
        /** PayeeIDs :  MAW=132 , RSS=129 , MLK=120 , GFP=126 , NMA=108 , JAP=127 , RJA=110 **/
        
        $divisions = Divisionhead::find(['user_id'=> 20])
                        //->select('division_head_id')
                        ->asArray()
                        ->all();
        $divisions = array_values($divisions);
        $searchModel = new RequestSearch();
        $searchModel->status_id = Request::STATUS_VERIFIED;
        $searchModel->cancelled = 0;
        
        if(Yii::$app->user->identity->user_id == 2){
            //$searchModel->payee_id = [129,120];
            $searchModel->division_id = [1];
            //$searchModel->user_id = 2;
        //}elseif(Yii::$app->user->identity->user_id == 4){
        //    $searchModel->division_id = [1,2,3];
            //$searchModel->payee_id = [129,117];
        }elseif(Yii::$app->user->identity->user_id == 3){
            $searchModel->division_id = [4];
            //$searchModel->payee_id = [62,70,54,55];
            //$searchModel->payee_id = [70,110];
        }elseif(Yii::$app->user->identity->user_id == 62){
            $searchModel->division_id = [5];
        }elseif(Yii::$app->user->identity->user_id == 70){
            $searchModel->division_id = [6];
        }elseif(Yii::$app->user->identity->user_id == 54){
            $searchModel->division_id = [7];
        }elseif(Yii::$app->user->identity->user_id == 55){
            $searchModel->division_id = [4,8];
        }
        /*if(Yii::$app->user->identity->user_id == 2){
            $searchModel->payee_id = [129,117];
            $searchModel->user_id = 2;
        }elseif(Yii::$app->user->identity->user_id == 4){
            $searchModel->division_id = [1,2,3];
            $searchModel->user_id = 4;
            //$searchModel->payee_id = [129,117];
        }elseif(Yii::$app->user->identity->user_id == 20){
            $searchModel->division_id = [4];
            $searchModel->user_id = 20;
        }*/
        //$searchModel->user_id = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('validateindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'divisions' => $divisions,
        ]);
    }
    
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionProcessingindex()
    {
        $searchModel = new RequestSearch();
        $searchModel->status_id = Request::STATUS_VALIDATED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('processingindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Request model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id); 
        $_obligationType = $model->obligation_type_id;

        $params = $this->checkAttachments($model);
        
        $request_status = $this->checkStatus($model->status_id);
        
        $blocks = Blockchain::find()->where(['index_id' => $id, 'scope' => 'Request'])->all();
        
        $attachmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAttachments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);

        $budgetallocationassignmentDataProvider = new ActiveDataProvider([
            'query' => $model->getBudgetallocationassignments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /* Bug #001 : Error when printing DV */
            if($model->status_id >= 40){
                if($_obligationType != $_POST['Request']['obligation_type_id']){
                    $chain = Blockchain::find()
                        ->where(['index_id' => $model->request_id, 'scope' => 'Request'])
                        ->orderBy(['blockchain_id' => SORT_DESC])
                        ->one();
                    if($_POST['Request']['obligation_type_id'] == 1)
                        $status = '40';
                    else
                        $status = '58';
                    $chain->data = substr($chain->data, 0, -2).$status;
                    $chain->save();
                }
            }
            /* End */

            /* Update related OSDV */
            if($model->osdv){
                $model->osdv->type_id = $_POST['Request']['obligation_type_id'];
                $model->osdv->save();
            }

            Yii::$app->session->setFlash('kv-detail-success', 'Request Updated! - ');
        }
        
        $CurrentUser= User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
        return $this->render('view', [
            'model' => $model,
            'attachmentsDataProvider' => $attachmentsDataProvider,
            'budgetallocationassignmentDataProvider' => $budgetallocationassignmentDataProvider,
            'request_status' => $request_status,
            'params' => $params,
            'user' => $CurrentUser,
            'blocks' => $blocks,
        ]);
    }
    
    public function actionViewpayroll($id)
    {
        $model = $this->findModel($id); 
        
        $params = $this->checkAttachments($model);
        
        $request_status = $this->checkStatus($model->status_id);
        
        $attachmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAttachments(),
            'pagination' => false,
        ]);
        
        $payrollDataprovider = new ActiveDataProvider([
            'query' => $model->getPayrollitems(),
            'pagination' => false,
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('kv-detail-success', 'Request Updated!');
        }
        
        $CurrentUser= User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
        return $this->render('view', [
            'model' => $model,
            'attachmentsDataProvider' => $attachmentsDataProvider,
            'payrollDataprovider' => $payrollDataprovider,
            'request_status' => $request_status,
            'params' => $params,
            'user' => $CurrentUser,
        ]);
    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Request();
        
        date_default_timezone_set('Asia/Manila');
        $model->request_date=date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
            
            $model->request_number = Request::generateRequestNumber();
            $model->created_by = Yii::$app->user->identity->user_id;
            
            if($model->save(false))
                return $this->redirect(['view', 'id' => $model->request_id]);
            
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionCreatepayroll()
    {
        $model = new Request(['scenario' => 'payroll']);

        date_default_timezone_set('Asia/Manila');
        $model->request_date=date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
            
            $model->request_number = Request::generateRequestNumber();
            $model->created_by = Yii::$app->user->identity->user_id;
            $model->status_id = Request::STATUS_SUBMITTED;
            $model->payroll = true;
            
            if($model->save(false))
                return $this->redirect(['viewpayroll', 'id' => $model->request_id]);
            
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_payroll_form', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_payroll_form', [
                        'model' => $model,
            ]);
        }
    }
    
    /*public function actionPayrollitems()
    {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        
        $searchModel = new CreditorSearch();
        
        //creditor_type_id
        //Payroll Regular(13), Payroll COntractual(14), MC Benefits(15), Hazard Contractual(16), Cash Award / Special Award(33)
        if($model->request_type_id == 13 || $model->request_type_id == 15){
            $searchModel->creditor_type_id = 1;
        }elseif($model->request_type_id == 14 || $model->request_type_id == 16){
            $searchModel->creditor_type_id = 2;
        }elseif($model->request_type_id == 33){
            $searchModel->creditor_type_id = [1,2];
        }elseif($model->request_type_id == 34){
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
    }*/
    
    public function actionViewattachments()
    {
        $model = $this->findModel($_GET['id']);
        date_default_timezone_set('Asia/Manila');
        
        if (Yii::$app->request->post()) {
            foreach($model->requesttype->requesttypeattachments as $requesttypeattachment)
            {
                $modelRequestattachment = new Requestattachment();
                $modelRequestattachment->request_id = $model->request_id;
                //$modelRequestattachment->name = $requesttypeattachment->attachment->name;
                $modelRequestattachment->attachment_id = $requesttypeattachment->attachment_id;
                $modelRequestattachment->last_update = date("Y-m-d H:i:s");
                $modelRequestattachment->save(false);
            }
            
            $action = $model->payroll ? 'viewpayroll' : 'view';
            return $this->redirect([$action.'?id='.$model->request_id]);
        }
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_info', ['model'=>$model]);   
        }
        
    }
    
    public function actionViewdocuments()
    {
        $model = $this->findModel($_GET['id']);
        date_default_timezone_set('Asia/Manila');
        
        $attachmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAttachments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);
        
        if (Yii::$app->request->post()) {
            
            return $this->redirect(['view', 'id' => $model->request_id]);  
        }
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_documents', ['model'=>$model, 'attachmentsDataProvider'=>$attachmentsDataProvider]);   
        }
        
    }
    
    public function actionRecent()
    {
        $model = $this->findModel($_GET['id']);
        
        $searchModel = new RequestSearch();
        $searchModel->payee_id = $_GET['payee_id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->post()) {
            
            return $this->redirect(['view', 'id' => $model->request_id]);  
        }
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_recent', ['model'=>$model, 'dataProvider'=>$dataProvider]);   
        }
        
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Request model.
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
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUpdateparticulars()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $response = Requesttype::findOne($_POST['requestTypeId']);
        if($response)
            return $response;
    }
    
    public function actionUploadattachment($id)
    {
        //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/uploads/';
        $model = Requestattachment::findOne($id);
        date_default_timezone_set('Asia/Manila');
        
        if (Yii::$app->request->post()) {
            $random = Yii::$app->security->generateRandomString(40);
            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            
            //$path = 'uploads/finance/request/' . $model->request->request_number.'/';
            $path = Yii::getAlias('@uploads') . "/finance/request/" . $model->request->request_number;
            if(!file_exists($path)){
                mkdir($path, 0755, true);
                $indexFile = fopen($path.'/index.php', 'w') or die("Unable to open file!");
            }
                
            $model->pdfFile->saveAs( $path ."/". $model->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            //$model->pdfFile->saveAs('uploads/finance/request/' . $model->request->request_number.'/'. $model->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            $model->filename = $model->request_attachment_id . $random . '.' . $model->pdfFile->extension;
            $model->last_update = date("Y-m-d H:i:s");
            $model->filecode = Requestattachment::generateCode($model->request_attachment_id);
            $model->status_id = $model->request->payroll ? 10 : 0;
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Document Successfully Uploaded!');
            
            $action = $model->request->payroll ? 'viewpayroll' : 'view';
            return $this->redirect([$action.'?id='.$model->request_id]);
        }
        
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_upload', ['model'=>$model]);   
        }else {
            return $this->render('_upload', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionUploadattachmenttest($id){
        $model = Requestattachment::findOne($id);
        date_default_timezone_set('Asia/Manila');
        
        if (Yii::$app->request->post()) {
            $random = Yii::$app->security->generateRandomString(40);
            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            
            //$path = 'uploads/finance/request/' . $model->request->request_number.'/';
            $path = Yii::getAlias('@uploads') . "/finance/request/" . $model->request->request_number;
            if(!file_exists($path)){
                mkdir($path, 0755, true);
                $indexFile = fopen($path.'/index.php', 'w') or die("Unable to open file!");
            }
                
            $model->pdfFile->saveAs( $path ."/". $model->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            //$model->pdfFile->saveAs('uploads/finance/request/' . $model->request->request_number.'/'. $model->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            $model->filename = $model->request_attachment_id . $random . '.' . $model->pdfFile->extension;
            $model->last_update = date("Y-m-d H:i:s");
            $model->filecode = Requestattachment::generateCode($model->request_attachment_id);
            $model->status_id = $model->request->payroll ? 10 : 0;
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Document Successfully Uploaded!');
            
            $action = $model->request->payroll ? 'viewpayroll' : 'view';
            return $this->redirect([$action.'?id='.$model->request_id]);
        }
        
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_viewattachment', ['model'=>$model]);   
        }else {
            return $this->render('_viewattachment', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionSignedattachment($id)
    {
        //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/uploads/';
        $model = Requestattachmentsigned::findOne($id);
        date_default_timezone_set('Asia/Manila');
        
        /*if (Yii::$app->request->post()) {
            $random = Yii::$app->security->generateRandomString(40);
            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            
            //$path = 'uploads/finance/request/' . $model->request->request_number.'/';
            $path = Yii::getAlias('@uploads') . "/finance/request/" . $model->request->request_number;
            if(!file_exists($path)){
                mkdir($path, 0755, true);
                $indexFile = fopen($path.'/index.php', 'w') or die("Unable to open file!");
            }
                
            $model->pdfFile->saveAs( $path ."/". $model->request_attachment_signed_id . $random . '.' . $model->pdfFile->extension);
            //$model->pdfFile->saveAs('uploads/finance/request/' . $model->request->request_number.'/'. $model->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            $model->filename = $model->request_attachment_id . $random . '.' . $model->pdfFile->extension;
            $model->last_update = date("Y-m-d H:i:s");
            $model->filecode = Requestattachment::generateCode($model->request_attachment_signed_id);
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Document Successfully Uploaded!');
            
            return $this->redirect(['view?id='.$model->request_id]);
        }*/
        
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_uploadsigned', ['model'=>$model]);   
        }else {
            return $this->render('_uploadsigned', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionMarkverified($id)
    {
        $model = Requestattachment::findOne($id);
        
        if (Yii::$app->request->post()) {
            $model->status_id = 10;
            if($model->save()){
                $index = $model->request_attachment_id;
                $scope = 'Requestattachment';
                $data = $model->request_attachment_id.':'.$model->last_update.':'.$model->filename.':'.$model->filecode.':'.$model->status_id;
                    
                $block = Blockchain::createBlock($index, $scope, $data);
            }
                Yii::$app->session->setFlash('success', 'Attachment has been Verified!');
            
            return $this->redirect(['view?id='.$model->request_id]);
        }
    }
    
    public function actionTogglestatus() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Requestattachment'][$index][$attr];
           $model = Requestattachment::findOne($ids);
           $model->$attr = $qty ? 10 : 0; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }

    public function actionTogglecancel() {
        if (Yii::$app->request->post('hasEditable')) {
            $ids = Yii::$app->request->post('editableKey');
            
            $index = Yii::$app->request->post('editableIndex');
            $attr = Yii::$app->request->post('editableAttribute');
            $qty = $_POST['Request'][$index][$attr];
            $model = Request::findOne($ids);
            $model->$attr = $qty ? 1 : 0; //$fmt->asDecimal($amt,2);
            if($model->save(false))
                return true;
            else
                return false;
        }
     }
    
    public function actionDeleteattachment(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $model = Requestattachment::findOne($_POST['key']);
        $file = 'uploads/finance/request/' . $model->request->request_number.'/'. $model->filename;
        
        if(unlink($file))
        {
            $model->filename = '';
            $model->save(false);
            return 'File deleted!';
        }else{
            return 'File was not deleted!';
        }
    }
    
    function checkStatus($status_id)
    {
        switch ($status_id) {
            case 20:
                $msg = 'Submitted';
                $alert = 'alert-info';
                break;
            case 30:
                $msg = 'Verified';
                $alert = 'alert-info';
                break;
            case 40:
                $msg = 'Validated';
                $alert = 'alert-info';
                break;
            case 50:
                $msg = 'Certified Allotment Available';
                $alert = 'alert-info';
                break;
            case 55:
                $msg = 'Alloted';
                $alert = 'alert-info';
                break;
            case 60:
                $msg = 'Certified Funds Available';
                $alert = 'alert-info';
                break;
            case 65:
                $msg = 'Charged';
                $alert = 'alert-info';
                break;
            case 70:
                $msg = 'Approved for Disbursement';
                $alert = 'alert-info';
                break;
            case 80:
                $msg = 'Completed';
                $alert = 'alert-success';
                break;
            default:
                $msg = '---';
                $alert = 'alert-info';
        }

        return [
            'msg' => $msg,
            'alert' => $alert,
        ];
    }
    
    function checkAttachments($modelAttachments)
    {
        $files = 0;
        $count = count($modelAttachments->attachments);
        $txt = '';
        $result = 0;
        if($count){
            foreach($modelAttachments->attachments as $attachment){
                $attachment_exist = Requestattachment::hasAttachment($attachment->request_attachment_id);
                //$txt .= '-'.$attachment->request_attachment_id; // debugging purposes

                $files += $attachment_exist;
                $txt .= '-'.$attachment_exist;
            }
            $result = $count - $files; 
            switch ($result) {
                case false:
                    $btnClass = 'btn btn-success';
                    $btnStatus = true;
                    break;
                case ($result < $count):
                    $btnClass = 'btn btn-warning';
                    $btnStatus = false;
                    break;
                case ($result == $count):
                    $btnClass = 'btn btn-danger';
                    $btnStatus = false;
                    break;
            }
        }else{
            $btnClass = 'btn btn-danger';
            $btnStatus = false;
        }
        
        return [
            'trace' => $txt,
            'btnClass' => $btnClass,
            'btnStatus' => $btnStatus,
            'requiredDocs' => $count,
            'files' => $files,
            'result' => $result
        ];
    }
    
    function checkAttachmentsVerified($modelAttachments)
    {
        $files = 0;
        $count = count($modelAttachments->attachments);
        $txt = '';
        $result = 0;
        
        $notVerified = Requestattachment::find()->where(['request_id' => $modelAttachments->request_id, 'status_id' => 0])->all();
        if($notVerified)
            return false;
        else
            return true;
    }
    
    function actionSubmitforverification()
    {
        $model = $this->findModel($_GET['id']);
        
        $params = $this->checkAttachments($model);
        $eligibleToSubmit = $params['btnStatus'];
        
        if($eligibleToSubmit){
            if (Yii::$app->request->post()) {
                $model->status_id = Request::STATUS_SUBMITTED; //20
                if($model->save(false)){
                    
                    $index = $model->request_id;
                    $scope = 'Request';
                    
                    $particulars = (strlen($model->particulars) > 200 ) ? substr($model->particulars, 0, 200) : $model->particulars;
                    $data = $model->request_number.':'.$model->request_date.':'.$model->request_type_id.':'.$model->payee_id.':'.$particulars.':'.$model->amount.':'.$model->status_id;
                    
                    $block = Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS(
                        '', 
                        2, 
                        $recipient->primary->sms.','
                        .$recipient->secondary->sms, 
                        'Request for Verification', 
                        $content, 
                        'FAIMS', 
                        $this->module->id, 
                    $this->action->id);
                    
                    Yii::$app->Notification->sendEmail(
                        '', 
                        2, 
                        $recipient->primary->email.','
                        .$recipient->secondary->email, 
                        'Request for Verification', 
                        $content, 
                        'FAIMS', 
                        $this->module->id, 
                        $this->action->id);
                    
                    Yii::$app->session->setFlash('success', 'Request submitted for Verification!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $model->request_id]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_submitforverification', ['model'=>$model]);   
            }else {
                return $this->render('_submitforverification', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_noteligible', ['model'=>$model]);   
            }
        }
    }
    
    function actionSubmitforvalidation()
    {
        $model = $this->findModel($_GET['id']);
        
        $eligibleToSubmit = $this->checkAttachmentsVerified($model);
        //$eligibleToSubmit = $params['btnStatus'];
        
        if(Yii::$app->user->can('access-finance-verification') && $eligibleToSubmit){
            if (Yii::$app->request->post()) {
                $model->status_id = Request::STATUS_VERIFIED; //30
                if($model->save(false)){
                    
                    $index = $model->request_id;
                    $scope = 'Request';
                    $particulars = (strlen($model->particulars) > 200 ) ? substr($model->particulars, 0, 200) : $model->particulars;
                    $data = $model->request_number.':'.$model->request_date.':'.$model->request_type_id.':'.$model->payee_id.':'.$particulars.':'.$model->amount.':'.$model->status_id;
                    $block = Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['division_id' => $model->division_id, 'status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms, 'Request for Validation', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Submitted!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $model->request_id]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_submitforvalidation', ['model'=>$model]);   
            }else {
                return $this->render('_submitforvalidation', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax( Yii::$app->user->can('access-finance-verification') ? '_noteligibleforvalidation' : '_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    function actionValidate()
    {
        $model = $this->findModel($_GET['id']);
        
        if(Yii::$app->user->can('access-finance-validation')){
            if (Yii::$app->request->post()) {
                //$model->status_id = ($model->obligation_type_id == 1) ? Request::STATUS_VALIDATED : Request::STATUS_ALLOTTED ; //40 : 55
                $model->status_id = ($model->obligation_type_id == 1) ? Request::STATUS_VALIDATED : 58 ; //40 : 58
                if($model->save(false)){
                    
                    $index = $model->request_id;
                    $scope = 'Request';
                    $particulars = (strlen($model->particulars) > 200 ) ? substr($model->particulars, 0, 200) : $model->particulars;
                    $data = $model->request_number.':'.$model->request_date.':'.$model->request_type_id.':'.$model->payee_id.':'.$particulars.':'.$model->amount.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms, 'Request for Obligation', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Validated!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $model->request_id]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_validate', ['model'=>$model]);   
            }else {
                return $this->render('_validate', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    public static function createBlock($index)
    {
        $request = Request::findOne($index);

        $index = $request->request_id;
        $scope = 'Request';
        $timestamp = time();
        $particulars = (strlen($model->particulars) > 200 ) ? substr($model->particulars, 0, 200) : $model->particulars;
        
        $data = $request->request_number.':'.$request->request_date.':'.$request->request_type_id.':'.$request->payee_id.':'.$particulars.':'.$request->amount.':'.$request->status_id;
        
        $block = new Blockchain();
        $block->index_id = $index;
        $block->scope = $scope;
        $block->timestamp = $timestamp;
        $block->data = $data;
        $block->hash = $block->calculateHash();
        $block->nonce = $timestamp;

        $block->save();
    }
    
    function actionComments()
    {
        $searchModel = new CommentSearch();
        $searchModel->component_id = Comment::COMPONENT_ATTACHMENT;
        $searchModel->record_id = $_GET['record_id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        //$comments = Comment::find()->where(['component_id' => Comment::COMPONENT_ATTACHMENT, 'record_id' => $_GET['record_id']])->all();

        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_comments', [
                    'searchModel' => $searchModel,
                    'dataProvider'=>$dataProvider
                ]);   
        }else {
            return $this->render('_comments', [
                        //'model' => $model,
            ]);
        }
    }
    
    function actionMigrate()
    {
        $model1 = Disbursement::find()->orderBy(['dv_date' => SORT_ASC])->all();
        return $this->render('migrate', [
                    'model' => $model1,
        ]); 
    }
    
    public function actionUpdatedistrict() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Request'][$index][$attr];
           $model = Request::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
    
    function actionPrintos($id)
    {
        $report = new Report();
        $report->obligationrequest($id);
    }
    
    function actionPrintdv($id, $boxA = null, $boxCD = null)
    {
        $report = new Report();
        $report->disbursementvoucher($id, $boxA, $boxCD);
    }
    
    function actionPrintdvpayroll($id)
    {
        $report = new Report();
        $report->disbursementvoucherpayroll($id);
    }
    
    public function actionAddcreditor() 
    {
        $model = new Demo; // your model can be loaded here

        // Check if there is an Editable ajax request
        if (isset($_POST['hasEditable'])) {
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // read your posted model attributes
            if ($model->load($_POST)) {
                // read or convert your posted information
                $value = $model->name;

                // return JSON encoded output in the below format
                return ['output'=>$value, 'message'=>''];

                // alternatively you can return a validation error
                // return ['output'=>'', 'message'=>'Validation error'];
            }
            // else if nothing to do always return an empty JSON encoded output
            else {
                return ['output'=>'', 'message'=>''];
            }
        }

        // Else return to rendering a normal view
        return $this->render('view', ['model'=>$model]);
    }
    
    /*public function actionSigneduploadindex()
    {
        $model = new Requestattachmentsigned;
        
        return $this->render('signeduploadindex', ['model'=>$model]);
    }*/
    
    public function actionSigneduploadindex()
    {
        $model = new Requestattachmentsigned;
        date_default_timezone_set('Asia/Manila');
        //$path = 'D:/xampp/htdocs/faims/frontend/web/uploads'; 
        $path = 'D:/cashier'; 
        //$path = Yii::getAlias('@uploads');
        
        if (Yii::$app->request->post()) {
            $random = Yii::$app->security->generateRandomString(40);
            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            //
            $requestattachment = Requestattachment::find()->where(
                ['filecode' => '68GKJO1X1PY']
                //['filecode' => $model->pdfFile->baseName]
            )->one();
            
            //$path = Yii::getAlias('@uploads') . "/finance/request/" . $requestattachment->request->request_number;
            //$path = Yii::getAlias('@uploads');
            /*if(!file_exists($path)){
                mkdir($path, 0755, true);
                $indexFile = fopen($path.'/index.php', 'w') or die("Unable to open file!");
            }*/
            
            //request_attachment_signed_id 	request_attachment_id 	filename 	status_id 	last_update
            //$model->pdfFile->saveAs( $path ."/". $model->request_attachment_id . $random . '_signed.' . $model->pdfFile->extension);
            $model->pdfFile->saveAs( $path ."/_signed.pdf");

            $model->filename = $model->request_attachment_id . $random . '.' . $model->pdfFile->extension;
            $model->request_attachment_id = $requestattachment->request_attachment_id;
            $model->last_update = date("Y-m-d H:i:s");
            $model->filecode = Requestattachment::generateCode($model->request_attachment_id);
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Document Successfully Uploaded!');
            
            //return $this->redirect(['signeduploadindex?id='.$model->request_id]);
        }
        
        return $this->render('signeduploadindex', ['model' => $model, 'path' => $path]);

    }
    
    public function actionUploadsigned()
    {
        //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/uploads/';
        
        $model = new Requestattachmentsigned;
        date_default_timezone_set('Asia/Manila');
        
        if (Yii::$app->request->post()) {
            $random = Yii::$app->security->generateRandomString(40);
            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            
            $requestattachment = Requestattachment::find()->where(
                //['filecode' => '68GKJO1X1PY']
                ['filecode' => $model->pdfFile->baseName]
            )->one();
            
            $path = Yii::getAlias('@uploads') . "/finance/request/". $requestattachment->request->request_number;
            if(!file_exists($path)){
                mkdir($path, 0755, true);
                $indexFile = fopen($path.'/index.php', 'w') or die("Unable to open file!");
            }
            
            //request_attachment_signed_id 	request_attachment_id 	filename 	status_id 	last_update
            $model->pdfFile->saveAs( $path ."/". $requestattachment->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            //$model->pdfFile->saveAs( $path ."/_signed.pdf");

            $model->filename = $model->request_attachment_id . $random . '.' . $model->pdfFile->extension;
            $model->request_attachment_id = $requestattachment->request_attachment_id;
            $model->last_update = date("Y-m-d H:i:s");
            $model->filecode = $requestattachment->filecode;
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Document Successfully Uploaded!');
            
            return $this->render('signeduploadindex', ['model' => $model]);
        }
        
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_uploadsigned', ['model'=>$model]);   
        }else {
            return $this->render('_uploadsigned', [
                        'model' => $model,
            ]);
        }
    }
    
    private function actionCreateRequestBlockchain($index, $status)
    {
        //$index = $model->request_id;
        $model = $this->findModel($index); 
        $scope = 'Request';
        
        $data = $model->request_number.':'.$model->request_date.':'.$model->request_type_id.':'.$model->payee_id.':'.$model->particulars.':'.$model->amount.':'.$model->status_id;
        Blockchain::createBlock($index, $scope, $data);
    }
    
    public function actionTracking()
    {
        $model = Request::find()
            ->where(['request_id' => $_GET['id']])
            ->one();
        
        /* Tracking will differ through the ff use cases:  
         * 1. by payroll (boolean)
         * 2. obligation_type_id (regular fund, scholarship fund, trust fund, mds - trust fund)
         *    also know as FundSource
        */
        
        switch ($model->obligation_type_id) {
          case 1:

            $status = [
                'submitted' => Request::submitted($model->request_id, $model->payroll, $model->status_id),
                'verified' => Request::verified($model->request_id, $model->payroll, $model->status_id),
                'validated' =>  Request::validated($model->request_id, $model->status_id),
                'certified_allotment' => Request::certified_allotment(
                    $model->request_id, 
                    $model->osdv ? $model->osdv->osdv_id : NULL, 
                    $model->status_id),
                'allotted' => Request::allotted($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, $model->status_id),
                'certified_funds' => Request::certified_funds_Reg_Fund($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->obligation_type_id, $model->status_id),
                'charged' => Request::charged($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->status_id),
                'approved' => Request::approved($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->status_id),
                'completed' => Request::completed($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->status_id),
            ];  
            break;
                
          case 2:
            
            $status = [
                'submitted' => Request::submitted($model->request_id, $model->payroll, $model->status_id),
                'verified' => Request::verified($model->request_id, $model->payroll, $model->status_id),
                'validated' =>  Request::for_disbursement($model->request_id, $model->status_id),
                'certified_funds' => Request::certified_funds($model->request_id, $model->request_id, 'Request', $model->obligation_type_id, $model->status_id),
                'charged' => Request::charged(
                    $model->request_id, 
                    $model->osdv ? $model->osdv->osdv_id : NULL, 
                    'Osdv', 
                    $model->status_id
                ),
                'approved' => Request::approved($model->request_id, $model->osdv->osdv_id, 'Osdv', $model->status_id),
                'completed' => Request::completed($model->request_id, $model->osdv->osdv_id, 'Osdv', $model->status_id),
            ];   
            break;
                
          case 3:
            
            $status = [
                'submitted' => Request::submitted($model->request_id, $model->payroll, $model->status_id),
                'verified' => Request::verified($model->request_id, $model->payroll, $model->status_id),
                'validated' =>  Request::for_disbursement($model->request_id, $model->status_id),
                'certified_funds' => Request::certified_funds($model->request_id, 'Request', $model->obligation_type_id, $model->status_id),
                'charged' => Request::charged(
                    $model->osdv ? $model->osdv->osdv_id : NULL, 
                    'Osdv', 
                    $model->status_id
                ),
                'approved' => Request::approved($model->osdv->osdv_id, 'Osdv', $model->status_id),
                'completed' => Request::completed($model->osdv->osdv_id, 'Osdv', $model->status_id),
            ]; 
            break;
                
          default:
            //code to be executed if n is different from all labels;
        }
        
        

        /*
            STATUS_CREATED = 10;   
            STATUS_SUBMITTED = 20; // end user
            STATUS_VERIFIED = 30;  // finance verification team
            STATUS_VALIDATED = 40;  // Head of the Requesting Unit (ARD)
            STATUS_CERTIFIED_ALLOTMENT_AVAILABLE = 50; // Head of Budget Unit (Budget Officer)
            STATUS_ALLOTTED = 55; // finance processing team / budgetting staff
            STATUS_FOR_DISBURSEMENT = 58; // finance processing team / budgetting staff
            STATUS_CERTIFIED_FUNDS_AVAILABLE = 60; // Head of the Accounting Unit (Accountant)
            STATUS_CHARGED = 65; // finance processing team / accounting staff
            STATUS_APPROVED_PARTIAL = 67;  // Head of Agency (Regional Director / OIC)
            STATUS_APPROVED_FOR_DISBURSEMENT = 70;  // Head of Agency (Regional Director / OIC)
            STATUS_COMPLETED = 80; // 
            STATUS_RATED = 90;     // end user
        */
        
        /*
        $status = [
            'submitted' => Request::submitted($model->request_id, $model->payroll, $model->status_id),
            'verified' => Request::verified($model->request_id, $model->payroll, $model->status_id),
            'validated' => ($model->obligation_type_id == 1) ? Request::validated($model->request_id, $model->status_id) : Request::for_disbursement($model->request_id, $model->osdv->osdv_id, $model->status_id),
            'certified_allotment' => ($model->obligation_type_id == 1) ? Request::certified_allotment($model->request_id, $model->osdv->osdv_id, $model->status_id) : null,
            'allotted' => ($model->obligation_type_id == 1) ? Request::allotted($model->osdv->osdv_id, $model->status_id) : null,
//            'disbursed' => ($model->obligation_type_id == 1) ? null : Request::for_disbursement($model->osdv->osdv_id, $model->status_id),
//            'obligated' => Request::obligated($model->osdv->osdv_id, $model->status_id),
            'certified_funds' => Request::certified_funds($model->request_id, $model->osdv->osdv_id, $model->obligation_type_id, $model->status_id),
//            'disbursed' => ($model->status_id >= 60) ? 'bg-aqua' : 'bg-gray',
            'charged' => ($model->status_id >= 65) ? 'bg-aqua' : 'bg-gray',
            'approved_partial' => ($model->status_id >= 67) ? 'bg-aqua' : 'bg-gray',
            'approved' => ($model->status_id >= 70) ? 'bg-aqua' : 'bg-gray',
            'completed' => ($model->status_id >= 80) ? 'bg-aqua' : 'bg-gray',
        ];
        */
        
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_tracking2', [
                    'model' => $model,
                    'status' => $status,
                ]);   
        }else {
            return $this->render('_tracking2', [
                    'model' => $model,
            ]);
        }
        
    }

    public function actionListprojecttype() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Projecttype::find()->andWhere(['type_id'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $projecttype) {
                    $out[] = ['id' => $projecttype['project_type_id'], 'name' => $projecttype['name']];
                    if ($i == 0) {
                        $selected = $projecttype['project_type_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionListproject() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Project::find()->andWhere(['project_type_id'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $project) {
                    $out[] = ['id' => $project['project_id'], 'name' => $project['name']];
                    if ($i == 0) {
                        $selected = $project['project_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }
}
