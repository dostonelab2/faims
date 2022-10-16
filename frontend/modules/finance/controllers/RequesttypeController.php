<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\Attachment;
use common\models\finance\Requesttype;
use common\models\finance\Requesttypeattachment;
use common\models\finance\RequesttypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * RequesttypeController implements the CRUD actions for Requesttype model.
 */
class RequesttypeController extends Controller
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
     * Lists all Requesttype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequesttypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Requesttype model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /*return $this->render('view', [
            'model' => $this->findModel($id),
        ]);*/
        
        $model = $this->findModel($id);
        $modelRequesttypeattachment = Requesttypeattachment::find()->asArray()->all();
        
        $documents = Requesttypeattachment::find()
           ->select('attachment_id')
           ->where(['request_type_id' => $id, 'active' => 1])
           ->asArray()
           ->all();
        $docs = [];
        $count = 0;
        foreach($documents as $doc){
            $docs[$count] = $doc['attachment_id'];
            $count += 1;
        }
        $model->documents = $docs;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$model->request_number = Request::generateRequestNumber();
            //$model->created_by = Yii::$app->user->identity->user_id;
            
            //if($model->save(false))
                //return $this->redirect(['view', 'id' => $model->request_type_id]);    
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                        'model' => $model,
                        'modelRequesttypeattachment' => $modelRequesttypeattachment,
                        //'documents' => $docs,
            ]);
        } else {
            return $this->render('view', [
                        'model' => $model,
                        'modelRequesttypeattachment' => $modelRequesttypeattachment,
                        //'documents' => $docs,
            ]);
        }
    }
    
    public function actionView1($id)
    {
        $model = $this->findModel($id);
        $modelRequesttypeattachment = Requesttypeattachment::find()->asArray()->all();
        $documents = Attachment::find();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('kv-detail-success', 'Success Message');
            return $this->renderAjax('view', [
                        'model' => $model,
                        'modelRequesttypeattachment' => $modelRequesttypeattachment,
                        //'documents' => $documents,
            ]);
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                        'model' => $model,
                        'modelRequesttypeattachment' => $modelRequesttypeattachment,
                        //'documents' => $documents,
            ]);
        } else {
            return $this->render('view', [
                        'model' => $model,
                        'modelRequesttypeattachment' => $modelRequesttypeattachment,
                        //'documents' => $documents,
            ]);
        }
    }

    /**
     * Creates a new Requesttype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Requesttype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_type_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Requesttype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->renderAjax(['view', 'id' => $model->request_type_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Requesttype model.
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
     * Finds the Requesttype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Requesttype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requesttype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAddattachment()
    {
        $request_type_id = $_POST['requestTypeId'];
        $atachment_id = $_POST['attachmentId'];
        $checked = $_POST['checked'];
        
        $attachment = Attachment::findOne($atachment_id);
        $request_type_attachment = Requesttypeattachment::find()->where([
                                    'request_type_id' => $request_type_id, 
                                    'attachment_id' => $atachment_id])->one();
        
        if($request_type_attachment)
        {
            //echo Json::encode(['message'=>$ppmp_item]);
            if($checked == 'true'){
                $request_type_attachment->active = 1;
                $request_type_attachment->save(false);
            }
            else{
                $request_type_attachment->active = 0;
                $request_type_attachment->save(false);
            }
        }else{
            $model = new Requesttypeattachment();
        
            $model->request_type_id = $request_type_id;
            $model->attachment_id = $attachment->attachment_id;
            $model->active = 1;
            $model->save(false);
        }
        
        echo Json::encode(['message'=>$atachment_id]);
    }
}
