<?php

namespace frontend\modules\cashier\controllers;

use Yii;
use common\models\cashier\Creditor;
use common\models\cashier\Creditortmp;
use common\models\cashier\CreditortmpSearch;
use common\models\system\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CreditortmpController implements the CRUD actions for Creditortmp model.
 */
class CreditortmpController extends Controller
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
     * Lists all Creditortmp models.
     * @return mixed
     */
    public function actionValidateindex()
    {
        $searchModel = new CreditortmpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Creditortmp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Creditortmp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new Creditortmp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->creditor_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/
    
    public function actionCreate()
    {
        $model = new Creditortmp();
        
        date_default_timezone_set('Asia/Manila');
        $model->date_request=date("Y-m-d H:i:s");
        $model->creditor_type_id = 3;
        
        if ($model->load(Yii::$app->request->post())) {
            
            $model->requested_by = Yii::$app->user->identity->user_id;
            
            if($model->save()){
                $content = 'New Payee / Creditor Request<br/><br/>';
                
                $content .= 'Name: '.$model->name.'<br/>';
                $content .= ' Address: '.$model->address.'<br/>';
                $content .= ' TIN: '.$model->tin_number.'<br/>';
                $content .= ' Creditor Type: '.$model->type->name.'<br/>';
                $content .= ' Requested by: '.Profile::findOne($model->requested_by)->fullname;
                    
                //Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms.','.$recipient->secondary->sms, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                Yii::$app->Notification->sendEmail('', 2, 'fass.procurement@gmail.com', 'New Payee / Creditor Request', $content, 'FAIMS', $this->module->id, $this->action->id);
                
                Yii::$app->session->setFlash('success', 'Payee / Creditor information sent!');
                return $this->redirect(['/finance/request/index']);
            }
                
            
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

    /**
     * Updates an existing Creditortmp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->creditor_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Creditortmp model.
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
     * Finds the Creditortmp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Creditortmp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Creditortmp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUpdatecreditor() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Creditortmp'][$index][$attr];
           $model = Creditortmp::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               
               if($attr == 'active'){
                   $creditor = new Creditor();
                   $creditor->creditor_type_id = $model->creditor_type_id;
                   $creditor->name = $model->name;
                   $creditor->address = $model->address;
                   $creditor->bank_name = $model->bank_name;
                   $creditor->account_number = $model->account_number;
                   $creditor->tin_number = $model->tin_number;
                   
                   $recipientContactNumber = Profile::findOne($model->requested_by)->contact_numbers;
                   $recipientEmail = Profile::findOne($model->requested_by)->user->email;
                   
                   $content = 'Approved Payee / Creditor Request<br/><br/>';
                
                   $content .= 'Name: '.$model->name.'<br/>';
                   $content .= 'Address: '.$model->address.'<br/>';
                   $content .= 'TIN: '.$model->tin_number.'<br/>';
                   $content .= 'Creditor Type: '.$model->type->name.'<br/>';
                   $content .= 'The new payee or creditor has been officially added to the system.'.'<br/><br/>';
                   $content .= 'Thank you!';
                   
                   if($creditor->save(false)){
                        Yii::$app->Notification->sendSMS('', 2, $recipientContactNumber, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                        Yii::$app->Notification->sendEmail('', 2, $recipientEmail, 'Approved Payee / Creditor Request', $content, 'FAIMS', $this->module->id, $this->action->id);
                       
                        return true;
                   }
                        
               }
           else
               return false;
       }
    }
}
