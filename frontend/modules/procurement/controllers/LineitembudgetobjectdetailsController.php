<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Lineitembudgetobjectdetails;
use common\models\procurement\LineitembudgetobjectdetailsSearch;
use common\models\procurement\Objectdetail;
use common\models\procurement\Objectdetailcategory;
use common\models\procurement\Position;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * LineitembudgetobjectdetailsController implements the CRUD actions for Lineitembudgetobjectdetails model.
 */
class LineitembudgetobjectdetailsController extends Controller
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
     * Lists all Lineitembudgetobjectdetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LineitembudgetobjectdetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lineitembudgetobjectdetails model.
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
     * Creates a new Lineitembudgetobjectdetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lineitembudgetobjectdetails();

        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post();
            $model->save();
        }else{
            $request = Yii::$app->request->get();
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'id' => $request['id'],
                    'objectdetails' => ArrayHelper::map(Objectdetail::find()->all(), 'object_detail_id', 'name'),
                    'objectdetailscategory' => ArrayHelper::map(Objectdetailcategory::find()->all(), 'object_detail_category_id', 'name'),
                ])  ;
            }
        }

    }

    /**
     * Updates an existing Lineitembudgetobjectdetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->line_item_budget_object_details_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lineitembudgetobjectdetails model.
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
     * Finds the Lineitembudgetobjectdetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lineitembudgetobjectdetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lineitembudgetobjectdetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionListobjectdetails() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            
            switch ($id) {
                case 1:
                    $list = Position::find()->asArray()->all();
                    
                    $selected  = null;
                    if ($id != null && count($list) > 0) {
                        $selected = '';

                        foreach ($list as $i => $object) {
                            $out[] = ['id' => $object['position_id'], 'name' => $object['name']];
                            if ($i == 0) {
                                $selected = $object['position_id'];
                            }
                        }
                        // Shows how you can preselect a value
                        echo Json::encode(['output' => $out, 'selected'=>$selected]);
                        //echo Json::encode(['output' => $out, 'selected'=>$selected]);
                        return;
                    }
                    break;
                    
                case 2:
                    //$list = Position::find()->andWhere(['position_id'=>$id])->asArray()->all();
                    break;
                default:
                    echo 'code to be executed if n is different from all labels';
            }
            

            
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }
    
    function processDepDropObjects()
    {
        
    }
}
