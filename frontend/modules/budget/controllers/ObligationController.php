<?php

namespace frontend\modules\budget\controllers;

use Yii;
use common\models\budget\Obligation;
use common\models\budget\ObligationSearch;
use common\models\procurement\Obligationrequest;
use common\models\procurement\ObligationrequestSearch;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\helpers\Json;

/**
 * ObligationController implements the CRUD actions for Obligation model.
 */
class ObligationController extends Controller
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
     * Lists all Obligation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObligationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Obligation model.
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
     * Creates a new Obligation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() 
    {
        $model = new Obligation();
        
        $searchModel = new ObligationrequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=5;
        
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['view', 'id' => $model->obligation_id]);    
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_select_obligation_request', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
            ]);
        } else {
            return $this->render('_select_obligation_request', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
            ]);
        }
    }

    /**
     * Updates an existing Obligation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->obligation_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Obligation model.
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
     * Finds the Obligation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Obligation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Obligation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionProcessrequest($id)
    {
        $model = $this->findModel($id);
        $modelObligationRequest = Obligationrequest::findOne($id);
        
        $obligationDetailsDataProvider = new ActiveDataProvider([
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
        
        return $this->render('processrequest', [
                'model' => $model,
                'modelObligationRequest' => $modelObligationRequest,
                'obligationDetailsDataProvider' => $obligationDetailsDataProvider,
            ]);
    }
}
