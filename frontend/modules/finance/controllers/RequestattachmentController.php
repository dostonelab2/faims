<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\Requestattachment;
use common\models\finance\RequestattachmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * RequestattachmentController implements the CRUD actions for Requestattachment model.
 */
class RequestattachmentController extends Controller
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
     * Lists all Requestattachment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestattachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Requestattachment model.
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
     * Creates a new Requestattachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Requestattachment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_attachment_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Requestattachment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_attachment_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Requestattachment model.
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
     * Finds the Requestattachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Requestattachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requestattachment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
//    const STATUS_ON_HAND = 10; 
//    const STATUS_TURNED_OVER = 20;
    public function actionMarkonhand()
    {
        $checked = $_POST['checked'];
        $requestAttachmentId = $_POST['request_attachment_id'];

        $requestattachment = Requestattachment::findOne($requestAttachmentId);
        
        if($requestattachment)
        {
            if($checked == 'true'){
                $requestattachment->original_doc_status_id = Requestattachment::STATUS_ON_HAND;
                $requestattachment->save(false);
            }
            else{
                $requestattachment->original_doc_status_id = 0;
                $requestattachment->save(false);
            }
            $out = 'Item Succefully Updated';
        }
    
        echo Json::encode(['message'=>$out]);
    }
}
