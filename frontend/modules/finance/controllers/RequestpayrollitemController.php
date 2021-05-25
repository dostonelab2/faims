<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\cashier\Creditor;
use common\models\finance\Requestpayrollitem;
use common\models\finance\RequestpayrollitemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Json;
/**
 * RequestpayrollitemController implements the CRUD actions for Requestpayrollitem model.
 */
class RequestpayrollitemController extends Controller
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
     * Lists all Requestpayrollitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestpayrollitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Requestpayrollitem model.
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
     * Creates a new Requestpayrollitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Requestpayrollitem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_payroll_item_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Requestpayrollitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_payroll_item_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Requestpayrollitem model.
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
     * Finds the Requestpayrollitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Requestpayrollitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requestpayrollitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAdditem() //call to add expenditure
    {
        $checked = $_POST['checked'];
        $creditorid = $_POST['creditorid'];
        $osdv_id = $_POST['osdvid'];
        $request_payroll_id = $_POST['requestpayrollid'];

        $creditor = Creditor::findOne($creditorid);
        $requestpayrollitem = Requestpayrollitem::find()->where([
                                    'osdv_id' => $osdv_id,  
                                    'creditor_id' => $creditor->creditor_id,  
                                        ])->one();
        
        if($requestpayrollitem)
        {
            //$out = 'chene - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            if($checked == 'true'){
                $requestpayrollitem->active = 1;
                $requestpayrollitem->save(false);
            }
            else{
                $requestpayrollitem->active = 0;
                $requestpayrollitem->save(false);
            }
            $out = 'Item Succefully Updated';
        }else{

            $model = new Requestpayrollitem();
            $model->osdv_id = $osdv_id;
            $model->request_payroll_id = $request_payroll_id;
            //$model->request_id = $id;
            $model->creditor_id = $creditor->creditor_id;
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
           $qty = $_POST['Requestpayrollitem'][$index][$attr];
           $model = Requestpayrollitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
}
