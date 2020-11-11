<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\Account;
use common\models\finance\AccountSearch;
use common\models\finance\Accounttransaction;
use common\models\finance\AccounttransactionSearch;
use common\models\finance\Taxcategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * AccounttransactionController implements the CRUD actions for Accounttransaction model.
 */
class AccounttransactionController extends Controller
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
     * Lists all Accounttransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccounttransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Accounttransaction model.
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
     * Creates a new Accounttransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Accounttransaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->account_transaction_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Accounttransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->account_transaction_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Accounttransaction model.
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
     * Finds the Accounttransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accounttransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accounttransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAdditems()
    {
        $id = $_GET['id'];
        $year = $_GET['year'];
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_additems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        } else {
            return $this->render('_additems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        }
    }
    
    public function actionAdditem() //call to add expenditure
    {
        $checked = $_POST['checked'];
        $accountid = $_POST['accountid'];
        $id = $_POST['id'];
        //$year = $_POST['year'];

        $account = Account::findOne($accountid);
        $accounttransaction = Accounttransaction::find()->where([
                                    'request_id' => $id, 'account_id' => $accountid, 
                                    //'year' => $year
                                        ])->one();
        
        if($accounttransaction)
        {
            //$out = 'chene - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            if($checked == 'true'){
                $accounttransaction->active = 1;
                $accounttransaction->save(false);
            }
            else{
                $accounttransaction->active = 0;
                $accounttransaction->save(false);
            }
            $out = 'Item Succefully Updated';
        }else{
            //$out = 'nuay - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            /**
                  `account_transaction_id`,
                  `request_id`,
                  `account_id`,
                  `transaction_type`,
                  `amount`,
                  `active`
            **/
            $model = new Accounttransaction();
            $model->request_id = $id;
            $model->account_id = $account->account_id;
            $model->transaction_type = Accounttransaction::DEBIT;
            $model->active = 1;
            $model->save(false);
            $out = 'Item Succefully Added';
        }
    
        echo Json::encode(['message'=>$out]);
    }
    
    public function actionUpdateamount() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Accounttransaction'][$index][$attr];
           $model = Accounttransaction::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
    
    public function actionApplytax()
    {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        
        if($model->load(Yii::$app->request->post())) {
            
            $data = Yii::$app->request->post();
            
            $taxcat = Taxcategory::findOne($data['Accounttransaction']['tax_category_id']);
            $model->rate1 = $taxcat->rate1;
            $model->rate2 = $taxcat->rate2;
            $model->tax_category_id = $data['Accounttransaction']['tax_category_id'];
            
            if($model->save(false)){
                $hasTax = $this->hasTax($model);
                $tax = $this->computeTax($model);
                if($hasTax){
                    $hasTax->amount = $tax;
                    $hasTax->save(false);
                }else{
                    $accounttransaction = new Accounttransaction();
                    $accounttransaction->request_id = $model->request_id;
                    $accounttransaction->account_id = 31;
                    $accounttransaction->transaction_type = $model->transaction_type;
                    $accounttransaction->amount = $tax;
                    $accounttransaction->tax_category_id = $data['Accounttransaction']['tax_category_id'];
                    $accounttransaction->tax_registered = $model->tax_registered;
                    $accounttransaction->debitcreditflag = $model->debitcreditflag;
                    $accounttransaction->active = 1;
                    $accounttransaction->save(false);
                }   
                Yii::$app->session->setFlash('success', 'Tax Successfully Applied!');
                return $this->redirect(['/finance/osdv/view', 'id' => $model->osdv->osdv_id]);
            }else{
                Yii::$app->session->setFlash('warning', $model->getErrors());                 
            }
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_applytax', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_applytax', [ ]);
        }
    }
    
    public function actionUpdateflag() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Accounttransaction'][$index][$attr];
           $model = Accounttransaction::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
    
    public function actionUpdatetax() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Accounttransaction'][$index][$attr];
           $model = Accounttransaction::findOne($ids);
           
           $tax = Taxcategory::find($_POST['Accounttransaction'][$index]['tax_category_id'])->one();
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           $model->rate1 = $tax->rate1;
           $model->rate2 = $tax->rate2;
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
    
    public function actionUpdatetaxreg() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Accounttransaction'][$index][$attr];
           $model = Accounttransaction::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if(!$model->taxable){
               $model->rate1 = 0.00;
               $model->rate2 = 0.00;
           }
           if($model->save(false)){
               if(!$model->taxable){
                $del = Accounttransaction::find()
                  ->where(['request_id'=>$model->request_id])
                  ->andwhere(['account_id'=>31])
                  ->one()
                  ->delete(); 
               }
               
               return true;
           }else
               return false;
       }
    }
    
    private function computeTax($model){
        $tax_amount = 0.00;
        
        switch ($model->tax_category_id) {
          case 1: //Goods (5% + 1%); check if 10k above; if below 10 check if supplier is tagged(amount divide by 1.12%)
                if($model->osdv->request->creditor->tagged || $model->tax_registered)
                    $taxable_amount = round($model->amount / 1.12, 2);
                else
                    $taxable_amount = $model->amount;

                if($model->osdv->request->creditor->tagged || $model->amount >= 10000.00){
                    $tax1 = round($taxable_amount * $model->rate1, 2);
                    $tax2 = round($taxable_amount * $model->rate2, 2);
                    $tax_amount = $tax1 + $tax2;
                }else{
                    $tax_amount = round($taxable_amount * $model->rate1, 2);
                }
            break;
                
          case 2: // Services  (5% + 2%); check if 10k above; if below 10 check if supplier is tagged(amount divide by 1.12%)
                if($model->osdv->request->creditor->tagged || $model->tax_registered)
                    $taxable_amount = round($model->amount / 1.12, 2);
                else
                    $taxable_amount = $model->amount;

                if($model->osdv->request->creditor->tagged || $model->amount >= 10000.00){
                    $tax1 = round($taxable_amount * $model->rate1, 2);
                    $tax2 = round($taxable_amount * $model->rate2, 2);
                    $tax_amount = $tax1 + $tax2;
                }else{
                    $tax_amount = round($taxable_amount * $model->rate1, 2);
                }
            break;
                
          case 3: //Rental  (5%)
                $tax_amount = round($model->amount * $model->rate1, 2);
            break;
                
          case 4: //Professional (10%)
                $tax_amount = round($model->amount * $model->rate1, 2);
            break;
                
          //default:
            //code to be executed if n is different from all labels;
        }
        
        /*** BACKUP CODE : Start ***/
        /*if($model->osdv->request->creditor->tagged || $model->tax_registered)
            $taxable_amount = round($model->amount / 1.12, 2);
        else
            $taxable_amount = $model->amount;
        
        if($model->osdv->request->creditor->tagged || $model->amount > 10000.00){
            $tax1 = round($taxable_amount * $model->rate1, 2);
            $tax2 = round($taxable_amount * $model->rate2, 2);
            $tax_amount = $tax1 + $tax2;
        }else{
            $tax_amount = round($taxable_amount * $model->rate1, 2);
        }*/
        /*** BACKUP CODE : End ***/
        
        return $tax_amount;
    }
    
    private function hasTax($model)
    {
        $hasTax = Accounttransaction::find()
                  ->where(['request_id'=>$model->request_id])
                  ->andwhere(['account_id'=>31])
                  ->one();
        if($hasTax)
            return $hasTax;
        else
            return false;
    }
}
