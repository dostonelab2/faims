<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\Obligationtype;
use common\models\finance\ObligationtypeSearch;
use common\models\finance\Os;
use common\models\finance\Dv;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObligationtypeController implements the CRUD actions for Obligationtype model.
 */
class ObligationtypeController extends Controller
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
     * Lists all Obligationtype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObligationtypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*$lastOSNumber = Os::find()->orderBy(['os_id'=>SORT_DESC])->one();
        $lastDVNumber_Regular = Dv::find()->where(['type_id' => 1])->orderBy(['dv_id'=>SORT_DESC])->one();
        $lastDVNumber_Scholarship = Dv::find()->where(['type_id' => 2])->orderBy(['dv_id'=>SORT_DESC])->one();
        $lastDVNumber_TF = Dv::find()->where(['type_id' => 3])->orderBy(['dv_id'=>SORT_DESC])->one();
        $lastDVNumber_MDS_TF = Dv::find()->where(['type_id' => 4])->orderBy(['dv_id'=>SORT_DESC])->one();*/
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            /*'$lastOSNumber' => $lastOSNumber,
            'lastDVNumber_Regular' => $lastDVNumber_Regular,
            'lastDVNumber_Scholarship' => $lastDVNumber_Scholarship,
            'lastDVNumber_TF' => $lastDVNumber_TF,
            'lastDVNumber_MDS_TF' => $lastDVNumber_MDS_TF,*/
        ]);
    }

    /**
     * Displays a single Obligationtype model.
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
     * Creates a new Obligationtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new Obligationtype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->type_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/
    
    public function actionCreate()
    {
        $model = new Obligationtype();
        
        if ($model->load(Yii::$app->request->post())) {
            
            //$model->created_by = Yii::$app->user->identity->user_id;
            
            if($model->save(false))
                return $this->redirect(['index']);
            
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionSkipos()
    {
        $model = new Os();
        date_default_timezone_set('Asia/Manila');
        $last_OS = Os::getLastOS();
        
        if ($model->load(Yii::$app->request->post())) {
            
            $save_os = 
            //for($i=0; $i<$model->numberOfOs; $i++){
                $lastOS = Os::find()->orderBy(['os_id'=>SORT_DESC])->one();
                $model->osdv_id = 0;
                $model->request_id = 0;
                $model->os_number = $model->generateOsNumber($_POST['Os']['classId'],date("Y-m-d H:i:s"));
                $model->os_date=date("Y-m-d H:i:s");
                $model->deleted = 0;
                //$model->save(false);
            //}
            
            if($model->save(false))
                return $this->redirect(['index']);
            
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_skipos', [
                        'model' => $model,
                        'last_OS' => $last_OS,
            ]);
        } else {
            return $this->render('_skipos', [
                        'model' => $model,
                        'last_OS' => $last_OS,
            ]);
        }
    }

    public function actionSkipdv()
    {
        $model = new Dv();
        
        if(!isset($model->obligation_type_id))
            $model->obligation_type_id = $_GET['type_id'];

        $last_DV = Dv::getLastDV($model->obligation_type_id, 1);
        
        if ($model->load(Yii::$app->request->post())) {

            $model = new Dv();
            $model->osdv_id = 0;
            $model->request_id = 0;
            $model->obligation_type_id = $_POST['Dv']['obligation_type_id'];
            $model->dv_number = Dv::generateDvNumber($model->obligation_type_id, $_POST['Dv']['classId'], date("Y-m-d H:i:s"));
            $model->dv_date = date("Y-m-d H:i:s");
            
            if($model->save(false))
                return $this->redirect(['index']);
            
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_skipdv', [
                        'model' => $model,
                        'last_DV' => $last_DV,
            ]);
        } else {
            return $this->render('_skipdv', [
                        'model' => $model,
                        'last_DV' => $last_DV,
            ]);
        }
    }
    /**
     * Updates an existing Obligationtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->type_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Obligationtype model.
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
     * Finds the Obligationtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Obligationtype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Obligationtype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
