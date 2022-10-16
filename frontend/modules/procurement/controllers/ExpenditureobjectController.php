<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Expenditureobject;
use common\models\procurement\ExpenditureobjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpenditureobjectController implements the CRUD actions for Expenditureobject model.
 */
class ExpenditureobjectController extends Controller
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
     * Lists all Expenditureobject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpenditureobjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Expenditureobject model.
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
     * Creates a new Expenditureobject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Expenditureobject();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->expenditure_object_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Expenditureobject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->expenditure_object_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Expenditureobject model.
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
     * Finds the Expenditureobject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expenditureobject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expenditureobject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    //updateobjectcode
    public function actionUpdateobjectcode() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Expenditureobject'][$index][$attr];
           $model = Expenditureobject::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
}
