<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Department;
use common\models\procurement\DepartmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

$session = Yii::$app->session;
$model = new Department();
/**
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends Controller
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
     * @param string $id
     * @param string $view
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new DepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $request = Yii::$app->request;
        if($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            return $this->renderPartial('_view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $model = new Department();
        $session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $session->set('savepopup',"executed");
                return $this->render('index');
        }else{
            return $this->renderPartial('_modal', [
                'model' => $model,
            ]) ;
        }
    }

    /**
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate()
    {
        $request = Yii::$app->request;
        if($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            $session = Yii::$app->session;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $session->set('updatepopup', "executed");
                return $this->redirect(['index']);
            } else {
                return $this->renderPartial('_modal', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $session = Yii::$app->session;
        $session->set('deletepopup',"executed");
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }
}
