<?php

namespace frontend\modules\budget\controllers;

use Yii;
use common\models\budget\Budgetallocationitemdetails;
use common\models\budget\BudgetallocationitemdetailsSearch;
use common\models\procurement\Program;
use common\models\procurement\ProgramSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
/**
 * BudgetallocationitemdetailsController implements the CRUD actions for Budgetallocationitemdetails model.
 */
class BudgetallocationitemdetailsController extends Controller
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
     * Lists all Budgetallocationitemdetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BudgetallocationitemdetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Budgetallocationitemdetails model.
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
     * Creates a new Budgetallocationitemdetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Budgetallocationitemdetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->budget_allocation_item_detail_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionAdditemdetails()
    {
        $id = $_GET['id'];
        $year = $_GET['year'];
        $program = $_GET['program'];
        $searchModel = new ProgramSearch();
        
        if($program == 'gia')
            $searchModel->fund_source_id <= 3;
        elseif($program == 'setup')
            $searchModel->fund_source_id = 4;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_additemdetails', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        } else {
            return $this->render('_additemdetails', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
                        'year' => $year,
            ]);
        }
    }
    
    public function actionAddprogram() 
    {
        /** Post Data
            id => budget_allocation_item_id,
            itemId => program_id
            year => year
            checked => checked
        **/
        
        $checked = $_POST['checked'];
        $itemId = $_POST['itemId'];
        $id = $_POST['id'];
        $year = $_POST['year'];

        $program = Program::findOne($itemId);
        $budgetallocationitemdetail = Budgetallocationitemdetails::find()->where([
                                    'budget_allocation_item_id' => $id, 'program_id' => $itemId, 
                                    //'year' => $year
                                        ])->one();
        
        if($budgetallocationitemdetail)
        {
            //$out = 'chene - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            if($checked == 'true'){
                $budgetallocationitemdetail->active = 1;
                $budgetallocationitemdetail->save(false);
            }
            else{
                $budgetallocationitemdetail->active = 0;
                $budgetallocationitemdetail->save(false);
            }
            $out = 'Item Succefully Updated';
        }else{
            //$out = 'nuay - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            /**
                budget_allocation_item_detail_id 	
                budget_allocation_item_id 	
                fund_source_id 	
                program_id 	
                section_id 	
                name 	
                amount 	
                year 	
                active 
            **/
            $model = new Budgetallocationitemdetails();
            $model->budget_allocation_item_id = $id;
            $model->fund_source_id = $program->fund_source_id;
            $model->program_id = $program->program_id;
            $model->section_id = $id;
            $model->name = $program->name;
            $model->active = 1;
            $model->save(false);
            $out = 'Program Succefully Added';
        }
    
        echo Json::encode(['message'=>$out]);
    }
    /**
     * Updates an existing Budgetallocationitemdetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->budget_allocation_item_detail_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Budgetallocationitemdetails model.
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
     * Finds the Budgetallocationitemdetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Budgetallocationitemdetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Budgetallocationitemdetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUpdateamount() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Budgetallocationitemdetails'][$index][$attr];
           $model = Budgetallocationitemdetails::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
    
    public function actionUpdatefundsource() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Budgetallocationitemdetails'][$index][$attr];
           $model = Budgetallocationitemdetails::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
}
