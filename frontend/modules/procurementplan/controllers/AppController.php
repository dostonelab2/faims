<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\AppSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ActiveDataProvider;
use frontend\modules\reports\app\appreport;
use yii\helpers\Url;



/**
 * AppController implements the CRUD actions for Ppmpitem model.
 */
class AppController extends Controller
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
     * Lists all Ppmpitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'xls' => false,
            //'exporter' => ''        
            ]);
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
    public function actionCreate()
    {
        $model = new Ppmpitem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ppmp_item_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
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
    public function actionExporttoexcel()
    {  
       $year = $_GET['year'];
        //$year = 2021;
        $query = Ppmpitem::find()->select([
            'description' => 'tbl_ppmp_item.description',
            'unit' => 'tbl_ppmp_item.unit',
            'q1' => 'SUM(tbl_ppmp_item.q1)',
            'q2' => 'SUM(tbl_ppmp_item.q2)',
            'q3' => 'SUM(tbl_ppmp_item.q3)',
            'q4' => 'SUM(tbl_ppmp_item.q4)',
            'q5' => 'SUM(tbl_ppmp_item.q5)',
            'q6' => 'SUM(tbl_ppmp_item.q6)',
            'q7' => 'SUM(tbl_ppmp_item.q7)',
            'q8' => 'SUM(tbl_ppmp_item.q8)',
            'q9' => 'SUM(tbl_ppmp_item.q9)',
            'q10' => 'SUM(tbl_ppmp_item.q10)',
            'q11' => 'SUM(tbl_ppmp_item.q11)',
            'q12' => 'SUM(tbl_ppmp_item.q12)',
            'cost' => 'tbl_ppmp_item.cost'
        ])
        /*->where([
            'tbl_ppmp_item.active' => 1,
            //'tbl_ppmp_item.status_id' => 2,
            'tbl_ppmp.year' => 2021,
            //'tbl_ppmp_item.item_id'=>1
            //'tbl_ppmp_item.item_id'=>1
        ])*/
        ->joinWith('ppmp')
        ->groupBy('tbl_ppmp_item.item_id');              
  
        $exporter = new appreport([
            'model' => $query,
            'year' => $year
        ]);
        //if(Yii::$app->request->isAjax){
        $exporter->loaddoc();
        ob_end_clean();
        $exporter->save('./templates/APP-CES_2020_FORM.xls');
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
}
