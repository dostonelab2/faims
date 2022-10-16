<?php

namespace frontend\modules\budget\controllers;

use Yii;
use common\models\procurement\Expenditure;
use common\models\procurement\ExpenditureSearch;
use common\models\procurement\Expenditureclass;
use common\models\procurement\Expendituresubclass;
use common\models\procurement\Expenditureobject;
use common\models\procurement\ExpenditureobjectSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * ExpenditureController implements the CRUD actions for Expenditure model.
 */
class ExpenditureController extends Controller
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
     * Lists all Expenditure models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpenditureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Expenditure model.
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
     * Creates a new Expenditure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Expenditure();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string) $model->ppmp_id]);
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

    /**
     * Updates an existing Expenditure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->expenditure_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Expenditure model.
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
     * Finds the Expenditure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expenditure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expenditure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAddexpenditures1()
    {
        /*$id = $_GET['id'];
        $year = $_GET['year'];
        if (Yii::$app->request->isAjax) {
            $ppmp = Ppmp::findOne($id); 
            if(!$ppmp->isMember())
            {
                return $this->renderAjax('_info', ['message'=>'You are not Authorized to do this action.']);   
            }elseif($ppmp->status_id == Ppmp::STATUS_SUBMITTED){
                return $this->renderAjax('_info', ['message'=>'This PPMP has been submitted for Approval.']);   
            }
        }*/
        
        $searchModel = new ExpenditureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        /*$queryExpendituress = Expenditure::find();
        $itemsDataProvider = new ActiveDataProvider([
            'query' => $queryExpendituress,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'item_category_id' => SORT_ASC,
                    'item_name' => SORT_ASC,
                ]
            ],
        ]);*/
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_addexpenditures', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        //'id' => $id,
                        //'year' => $year,
            ]);
        } else {
            return $this->render('_addexpenditures', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        //'id' => $id,
                        //'year' => $year,
            ]);
        }
    }
    
    public function actionAddexpenditures() //call for modal
    {
        if(isset($_GET['year'])){
            $year = $_GET['year'];
        }else{
            $year = date('Y');
        }
        $id = 1;
        $searchModel = new ExpenditureobjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_addexpenditures', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        } else {
            return $this->render('_addexpenditures', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        }
    }
    
    public function actionAddexpenditure() //call to add expenditure
    {
        /** Post Data
            itemId : 4
            checked : true
            ppmpId : 2
            year : 2019
        **/
        
        $checked = $_POST['checked'];
        $objectid = $_POST['objectid'];
        $year = $_POST['year'];

        
        $expenditure_object = Expenditureobject::findOne($objectid);
        $expenditure = Expenditure::find()->where([
                                    'expenditure_object_id' => $objectid, 
                                    'year' => $year])->one();
        
        if($expenditure)
        {
            //$out = 'chene - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            if($checked == 'true'){
                $expenditure->active = 1;
                $expenditure->save(false);
            }
            else{
                $expenditure->active = 0;
                $expenditure->save(false);
            }
            $out = 'Object Succefully Updated';
        }else{
            //$out = 'nuay - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            $model = new Expenditure();
            $model->expenditure_class_id = $expenditure_object->expenditureSubClass->expenditureClass->expenditure_class_id;
            $model->expenditure_subclass_id = $expenditure_object->expenditure_sub_class_id;
            $model->expenditure_object_id = $expenditure_object->expenditure_object_id;
            $model->year = $year;
            $model->name = $expenditure_object->name;
            $model->active = 1;
            $model->save(false);
            $out = 'Object Succefully Added';
        }
    
        echo Json::encode(['message'=>$out]);
    }
    
    public function actionUpdateamount() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Expenditure'][$index][$attr];
           $model = Expenditure::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
               //echo Yii::$app->session->setFlash('success', "Quantity updated.");
           else
               return false;
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }
}
