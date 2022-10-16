<?php

namespace frontend\modules\procurement\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use common\models\procurement\Ppmp;
use common\models\procurement\PpmpSearch;
use common\models\procurement\Division;
use common\models\procurement\Unit;
use common\models\procurement\Project;

/**
 * PpmpController implements the CRUD actions for Ppmp model.
 */
class PpmpController extends Controller
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
     * Lists all Ppmp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PpmpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ppmp model.
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
     * Creates a new Ppmp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ppmp();
        $model->division_id = Yii::$app->user->identity->profile->division_id;
        $divisions = Division::find()->all();
        $projects = Project::find()->all();

        $listDivisions = ArrayHelper::map($divisions,'division_id','name');
        //$listUnits = ArrayHelper::map($units,'unit_id','name');
        
        //$endYear = date('Y');
        $years = [];
        for($endYear = date('Y'); $endYear >= 2015; $endYear--){
            array_push($years, ['id' => $endYear, 'value' => $endYear]);
        }
        
        $charge_to = [['id'=>'1', 'name'=>'GAA'], ['id'=>'2', 'name'=>'Project']];
        $chargeTo = ArrayHelper::map($charge_to,'id','name');
        $projects = ArrayHelper::map($projects,'project_id','code');
        $years = ArrayHelper::map($years,'id','value');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ppmp_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listDivisions' => $listDivisions,
                'chargeTo' => $chargeTo,
                'projects' => $projects,
                'years' => $years,
            ]);
        }
    }

    /**
     * Updates an existing Ppmp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ppmp_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ppmp model.
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
     * Finds the Ppmp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ppmp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ppmp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
