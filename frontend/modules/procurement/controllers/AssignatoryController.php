<?php

namespace frontend\modules\procurement\controllers;
use Yii;
use common\models\procurement\Assignatory;
use common\models\procurement\AssignatorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * AssignatoryController implements the CRUD actions for Assignatory model.
 */
class AssignatoryController extends Controller
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
     * Lists all Assignatory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AssignatorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Assignatory model.
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
     * Creates a new Assignatory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Assignatory();
        $con =  Yii::$app->db;
        $command_employee = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`lastname`,', ', `tbl_profile`.`firstname` ,' ', `tbl_profile`.`middleinitial`, ' - ' , `tbl_profile`.`designation`) AS employeename
        FROM `tbl_profile`");
        $employees = $command_employee->queryAll();
        $listEmployee = ArrayHelper::map($employees, 'user_id', 'employeename');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->assignatory_id, 
                                    'listEmployee' => $listEmployee]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listEmployee' => $listEmployee
            ]);
        }
    }

    /**
     * Updates an existing Assignatory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $con =  Yii::$app->db;
        $command_employee = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`lastname`,', ', `tbl_profile`.`firstname` ,' ', `tbl_profile`.`middleinitial`, ' - ' , `tbl_profile`.`designation`) AS employeename
        FROM `tbl_profile`");
        $employees = $command_employee->queryAll();
        $listEmployee = ArrayHelper::map($employees, 'user_id', 'employeename');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->assignatory_id,
                                    'listEmployee' => $listEmployee]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'listEmployee' => $listEmployee
            ]);
        }
    }

    /**
     * Deletes an existing Assignatory model.
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
     * Finds the Assignatory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Assignatory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Assignatory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
