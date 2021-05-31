<?php

namespace frontend\modules\cashier\controllers;

use Yii;
use common\models\cashier\Lddapada;
use common\models\cashier\Lddapadaitem;
use common\models\cashier\LddapadaitemSearch;
use common\models\cashier\CreditorSearch;
use common\models\cashier\OsdvSearch;
use common\models\cashier\OsdvlddapitemSearch;
use common\models\finance\Request;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LddapadaitemController implements the CRUD actions for Lddapadaitem model.
 */
class LddapadaitemController extends Controller
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
     * Lists all Lddapadaitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LddapadaitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lddapadaitem model.
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
     * Creates a new Lddapadaitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lddapadaitem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->lddapada_item_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lddapadaitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->lddapada_item_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lddapadaitem model.
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
     * Finds the Lddapadaitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lddapadaitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lddapadaitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAdditems()
    {
        $id = $_GET['id'];
        /*$year = $_GET['year'];
        if (Yii::$app->request->isAjax) {
            $ppmp = Ppmp::findOne($id); 
            if(!$ppmp->isMember())
            {
                return $this->renderAjax('_info', ['message'=>'You are not Authorized to do this action.']);   
            }elseif($ppmp->status_id == Ppmp::STATUS_SUBMITTED){
                return $this->renderAjax('_info', ['message'=>'This PPMP has been submitted for Approval.']);   
            }
        }*/
        
        $searchModel = new CreditorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_additems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
            ]);
        } else {
            return $this->render('_additems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
            ]);
        }
    }
    
    public function actionAddcreditors()
    {
        $id = $_GET['id'];
        $typeId = $_GET['typeId'];

        $lddapada = Lddapada::findOne($id); 
        $searchModel = new OsdvlddapitemSearch();
//        $searchModel =    new OsdvSearch();
        $status_id = Request::STATUS_APPROVED_FOR_DISBURSEMENT;
        $searchModel->status_id = $status_id;
        $searchModel->type_id = $lddapada->type_id;
        $searchModel->lddapadaId = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_addcreditors', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
            ]);
        } else {
            return $this->render('_additems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
            ]);
        }
    }
    
}
