<?php

namespace frontend\modules\budget\controllers;

use Yii;

use common\models\budget\Budgetallocationitem;
use common\models\budget\BudgetallocationitemSearch;

use common\models\procurement\Expenditure;
use common\models\procurement\ExpenditureSearch;
use common\models\procurement\Expenditureclass;
use common\models\procurement\Expendituresubclass;
use common\models\procurement\Expenditureobject;
use common\models\procurement\ExpenditureobjectSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * BudgetallocationitemController implements the CRUD actions for Budgetallocationitem model.
 */
class BudgetallocationitemController extends Controller
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
     * Lists all Budgetallocationitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BudgetallocationitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Budgetallocationitem model.
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
     * Creates a new Budgetallocationitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Budgetallocationitem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->budget_allocation_item_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionAdditems()
    {
        $id = $_GET['id'];
        $year = $_GET['year'];
        $searchModel = new ExpenditureobjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_additems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        } else {
            return $this->render('_additems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        }
    }

    public function actionAdditem() //call to add expenditure
    {
        /** Post Data
            itemId : 4
            checked : true
            ppmpId : 2
            year : 2019
        **/
        
        $checked = $_POST['checked'];
        $objectid = $_POST['objectid'];
        $id = $_POST['id'];
        $year = $_POST['year'];

        $expenditure_object = Expenditureobject::findOne($objectid);
        $budgetallocationitem = Budgetallocationitem::find()->where([
                                    'budget_allocation_id' => $id, 'category_id' => $objectid, 
                                    //'year' => $year
                                        ])->one();
        
        if($budgetallocationitem)
        {
            //$out = 'chene - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            if($checked == 'true'){
                $budgetallocationitem->active = 1;
                $budgetallocationitem->save(false);
            }
            else{
                $budgetallocationitem->active = 0;
                $budgetallocationitem->save(false);
            }
            $out = 'Item Succefully Updated';
        }else{
            //$out = 'nuay - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            /**
                `budget_allocation_item_id`,
                `budget_allocation_id`,
                `name`,
                `code`,
                `category_id`,
                `amount`
            **/
            $model = new Budgetallocationitem();
            $model->expenditure_class_id = $expenditure_object->expenditureSubClass->expenditureClass->expenditure_class_id;
            $model->expenditure_subclass_id = $expenditure_object->expenditure_sub_class_id;
            $model->category_id = $expenditure_object->expenditure_object_id;
            $model->budget_allocation_id = $id;
            $model->name = $expenditure_object->name;
            $model->active = 1;
            $model->save(false);
            $out = 'Item Succefully Added';
        }
    
        echo Json::encode(['message'=>$out]);
    }
    
    public function actionUpdateamount() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Budgetallocationitem'][$index][$attr];
           $model = Budgetallocationitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
    /**
     * Updates an existing Budgetallocationitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->budget_allocation_item_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Budgetallocationitem model.
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
     * Finds the Budgetallocationitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Budgetallocationitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Budgetallocationitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
