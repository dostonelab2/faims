<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\SupplementalSearch;
use common\models\procurementplan\ItemSearch;
use common\models\procurementplan\RemainingSupplementalItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\web\HttpException;
use yii\data\ActiveDataProvider;
/**
 * SupplementalController implements the CRUD actions for Ppmpitem model.
 */
class SupplementalController extends Controller
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
                    //'submit' => ['POST'],
                    //'approve' => ['POST'],
                    //'index' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ppmpitem models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        //$attribute = Ppmpitem::find()->where(['ppmp_id' => Yii::$app->request->get('id'),'item_id' => 285]);
        $model = Ppmp::find()->where(['ppmp_id' => $id])->one();
        $searchModel = new SupplementalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $isMember = $model->isMember();
        //$isApproval = Yii::$app->user->can('approved-supplemental-ppmp');
      
        $countapproval = Ppmpitem::find()->Where([
                                    'ppmp_id' => $id, 
                                    'active' => 1,
                                    'supplemental' => 1,
                                    'status_id' => 1]);
        $countsubmit = Ppmpitem::find()->Where([
                                    'ppmp_id' => $id, 
                                    'active' => 1,
                                    'supplemental' => 1,
                                    'status_id' => 0]);
        $countapprovalDataProvider = new ActiveDataProvider([
            'query' => $countapproval,
        ]);
        $countsubmitDataProvider = new ActiveDataProvider([
            'query' => $countsubmit,
        ]);
        //if(Yii::$app->request->post()){
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
                'isMember' => $isMember,
                //'isApproval' => $isApproval,
                'countapprovalDataProvider' => $countapprovalDataProvider,
                'countsubmitDataProvider' => $countsubmitDataProvider,
                //'attribute' => $attribute,
            ]);
        //}else{
          // throw new HttpException(403, 'You are not allowed to perform this action.');
        //}
    }

    /**
     * Displays a single Ppmpitem model.
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
     * Creates a new Ppmpitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionAddsupplementalitems()
    {

        $id = $_GET['id'];
        $year = $_GET['year'];
        
        if (Yii::$app->request->isAjax) {
            $ppmp = Ppmp::findOne($id); 
            if(!$ppmp->isMember())
            {
                //return $this->renderAjax('_info', ['message'=>'You are not Authorized to do this action.']); 
                echo Yii::$app->session->setFlash('error', "You are not Authorized to do this action.");
                return $this->redirect(['index', 'id' => $id]); 
            }/*elseif($ppmp->status_id == Ppmp::STATUS_SUBMITTED){
                //return $this->renderAjax('_info', ['message'=>'This PPMP has been submitted for Approval.']); 
                echo Yii::$app->session->setFlash('error', "This PPMP has been submitted for Approval.");
                return $this->redirect(['index', 'id' => $id]); 
            }*/
        }
        
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
    
        
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if($post){
                $month = $post['month'];
                return $this->renderAjax('_additem', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
                        'month' => $month,
                        ]);
            }else{
                
                $month = 0;
                return $this->renderAjax('_additem', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
                        'month' => $month,
                        ]);

            }

        } else {
            
       
            return $this->redirect(['index', 'id' => $id]);

        }
    }



    /**
     * Updates an existing Ppmpitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ppmp_item_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ppmpitem model.
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
     * Finds the Ppmpitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ppmpitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ppmpitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionUpdateqty() {
       //$id = Yii::$app->request->get('id');

       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Ppmpitem'][$index][$attr];
           $model = Ppmpitem::findOne($ids);
           $month = Yii::$app->request->get('month');

        if($month == 1){
                $model->q1 = $qty;
           }
        if($month == 2){
                $model->q2 = $qty;
           }
        if($month == 3){
                $model->q3 = $qty;
           }
        if($month == 4){
                $model->q4 = $qty;
           }
        if($month == 5){
                $model->q5 = $qty;
           }
        if($month == 6){
                $model->q6 = $qty;
           }
        if($month == 7){
                $model->q7 = $qty;
           }
        if($month == 8){
                $model->q8 = $qty;
           }
        if($month == 9){
                $model->q9 = $qty;
           }
        if($month == 10){
                $model->q10 = $qty;
           }
        if($month == 11){
                $model->q11 = $qty;
           }
        if($month == 12){
                $model->q12 = $qty;
           }

           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
                return true;
               //return $this->redirect(['index?id=' . $id]);
               //return Yii::$app->session->setFlash('success', "Quantity updated.");
           else
                return false;
               //return $this->redirect(['index?id=' . $id]);
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }

    public function actionSubmit(){
        $item_id = Yii::$app->request->get('item_id');
        $ppmp_id = Yii::$app->request->get('ppmp_id');
        $model = Ppmpitem::find()->where(['ppmp_item_id' => $item_id])->one();
        $now = new Expression('NOW()');
        $user = Yii::$app->user->id;
        //$remainingitems = RemainingSupplementalItem::find()->where(['ppmp_item_id' => $id])->one();
        
       // if(Yii::$app->request->isAjax){
        if(Yii::$app->request->post()){
            $ppmpitem = new RemainingSupplementalItem();
            $ppmpitem->ppmp_item_id = $model->ppmp_item_id;
            $ppmpitem->q1 = $model->q1;
            $ppmpitem->q2 = $model->q2;
            $ppmpitem->q3 = $model->q3;
            $ppmpitem->q4 = $model->q4;
            $ppmpitem->q5 = $model->q5;
            $ppmpitem->q6 = $model->q6;
            $ppmpitem->q7 = $model->q7;
            $ppmpitem->q8 = $model->q8;
            $ppmpitem->q9 = $model->q9;
            $ppmpitem->q10 = $model->q10;
            $ppmpitem->q11 = $model->q11;
            $ppmpitem->q12 = $model->q12;
                //$ppmpitem->save(false);
            $model->status_id = 1;
            $model->submitted_date = $now;
            $model->submitted_user = $user;
            
            if($model->save(false) && $ppmpitem->save(false)){
                echo Yii::$app->session->setFlash('success', "Item succesfully submitted");
                return $this->redirect(['index?id=' . $ppmp_id]);

            }else{
                return $this->redirect(['index?id=' . $ppmp_id]);
            }
        }else{

        }

    }
    public function actionApprove(){
        $now = new Expression('NOW()');
        $item_id = Yii::$app->request->get('item_id');
        $ppmp_id = Yii::$app->request->get('ppmp_id');
        $model = Ppmpitem::find()->where(['ppmp_item_id' => $item_id])->one();
        $user = Yii::$app->user->id;

        if(Yii::$app->request->post()){
            //$status = Yii::$app->request->post();
            //$status_id = $status['status'];

            $model->status_id = 2;
            $model->approved_date = $now;
            $model->approved_user = $user;
            
            if($model->save(false)){
                echo Yii::$app->session->setFlash('success', "Item succesfully Approved");
                return $this->redirect(['index?id=' . $ppmp_id]);;
            }else{
                return $this->redirect(['index?id=' . $ppmp_id]);
            }


        }
    
    }
    public function actionApproveall($id){
        $now = new Expression('NOW()');
        $user = Yii::$app->user->id;
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post('status');
            if($data){
                Ppmpitem::updateAll([
                    'status_id' => $data,
                    'approved_date' => $now, 
                    'approved_user' => $user,
                ],
                    [
                    'ppmp_id' => $id,
                    'supplemental' => 1,
                    'status_id' => 1,
                ]);
                Yii::$app->session->setFlash('success', "Supplemental Items successfully approved all.");
                return $this->redirect(['index', 'id' => (string) $id]);
            }
      
        }else{
            throw new HttpException(403, 'You are not allowed to perform this action.');
        }
    
    }
    public function actionSubmitall($id){
        $now = new Expression('NOW()');
        $user = Yii::$app->user->id;
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post('status');
            if($data){
                Ppmpitem::updateAll([
                    'status_id' => $data,
                    'submitted_date' => $now, 
                    'submitted_user' => $user,
                ],
                    [
                    'ppmp_id' => $id,
                    'supplemental' => 1,
                    'status_id' => 0,
                ]);
                Yii::$app->session->setFlash('success', "Supplemental Items successfully submitted all.");
                return $this->redirect(['index', 'id' => (string) $id]);
            }
      
        }else{
            throw new HttpException(403, 'You are not allowed to perform this action.');
        }
    
    }
    public function actionAdditems($id){

        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$MonthDataProvider = $searchModel->searchMonth(Yii::$app->request->queryParams);

        return $this->renderAjax('__additem', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    
    }
    /*
    public function actionSelectmonth(){
        $id = Yii::$app->request->get('id');
        $year = Yii::$app->request->get('year');
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            $get = Yii::$app->request->get();
            $month = $get['month'];

            //var_dump($month);
            
            return $this->renderAjax('_additem', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
                        'month' => $month,
                    ]);
        }
    }*/
}
