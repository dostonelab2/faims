<?php

namespace frontend\modules\docman\controllers;

use Yii;
use common\models\docman\Document;
use common\models\docman\DocumentSearch;
use common\models\docman\Category;
use common\models\docman\Functionalunit;
use common\models\docman\Qmstype;

use kartik\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
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
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $qmstype = Qmstype::findOne(['qms_type_id'=> $_GET['qms_type_id']]);
        
        $category_menus = '';
        $categories = Category::find()->limit(3)->all();
        foreach($categories as $category){
            $category_menus .= Html::button($category->code, ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;']);
        }
        
//        if($qmstype->qms_type_id == 1){
        $toolbars = '';
        $units = Functionalunit::findAll(['qms_type_id'=> $_GET['qms_type_id']]);
        foreach($units as $unit){
            $toolbars .= Html::button($unit->code, ['title' => 'Approved', 'class' => 'btn btn-info', 'style'=>'width: 90px; margin-right: 6px;']);
        }
            
//            $toolbars = Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
//                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn-primary', 'style'=>'width: 90px; margin-right: 6px;']);
//        }elseif($qmstype->qms_type_id == 2){
//            $toolbars = Html::button('APPROVED', ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;']);
//        }
        
        return $this->render('index', [
            'qmstype'=>$qmstype,
            'category_menus'=>$category_menus,
            'toolbars'=>$toolbars,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
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
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Document();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->identity->user_id;
            $model->active = 1;
//            $model->remarks = '';
//            $model->payroll = $_POST['Osdv']['payroll'];
            /*if($model->save(false)){
                if($model->type_id == 1){
                    $os = new Os();
                    $os->osdv_id = $model->osdv_id;
                    $os->request_id = $model->request_id;
                    $os->os_number = Os::generateOsNumber($model->expenditure_class_id, $model->create_date);
                    $os->os_date = date("Y-m-d", strtotime($model->create_date));
                    $os->save(false);
                }
            
                $model->request->status_id = Yii::$app->user->can('access-finance-disbursement') ? Request::STATUS_FOR_DISBURSEMENT : Request::STATUS_ALLOTTED;
                $model->request->save(false);
                return $this->redirect(['view', 'id' => $model->osdv_id]);   
            }*/
                 
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
//                        'requests' => $requests,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
//                        'requests' => $requests,
            ]);
        }
        
        
        
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->document_id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->document_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Document model.
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
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
