<?php

namespace frontend\modules\budget\controllers;

use Yii;
use common\models\budget\Allocationadjustment;
use common\models\budget\AllocationadjustmentSearch;
use common\models\budget\Budgetallocationadjustment;
use common\models\procurement\Expenditureobject;
use common\models\procurement\ExpenditureobjectSearch;
use common\models\budget\Budgetallocationitem;
use common\models\budget\Budgetallocation;
use common\models\procurement\Section;
//use common\models\system\Usersection;

use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;



/**
 * AllocationadjustmentController implements the CRUD actions for Allocationadjustment model.
 */
class AllocationadjustmentController extends Controller
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
     * Lists all Allocationadjustment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AllocationadjustmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Allocationadjustment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new AllocationadjustmentSearch();
        $itemid = $_GET['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $itemid);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'dataProvider' => $dataProvider
            ]);
        } else {
            return $this->render('view', [
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * Creates a new Allocationadjustment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Allocationadjustment();
        $modelDeduction = new Allocationadjustment();

        $item_id = $item_id ?? $_GET['itemid'];
        $item_detail_id = $item_detail_id ?? $_GET['itemdetailid'];
        
        $model->new_item = 0;
        $model->item_id = $item_id;
        $model->item_detail_id = $item_detail_id;
        $model->source_item = $item_id + $item_detail_id;
        
        date_default_timezone_set('Asia/Manila');
        
        $budgetallocationitems = Budgetallocationitem::find()->with('budgetallocation', 'budgetallocation.section')->asArray()->all();
        $sections = Section::find()->orderBy(['division_id'=>SORT_ASC, 'section_id'=>SORT_ASC])->asArray()->all();
        $sections = ArrayHelper::map($sections, 'section_id', 'name');

        $listSections = ArrayHelper::map($budgetallocationitems, 'budget_allocation_item_id', 'name', 'budgetallocation.section.name');
        
        $budgetallocationitem = Budgetallocationitem::find()->where([
                                    'budget_allocation_item_id' => $item_id, //'category_id' => $objectid, 
                                        ])->one();
        
        if ($model->load(Yii::$app->request->post())) {
            
            if($model->new_item){
                $expenditure_object = Expenditureobject::findOne($model->new_item);
                
                $new_budgetallocationitem = new Budgetallocationitem();
                $new_budgetallocationitem->expenditure_class_id = $expenditure_object->expenditureSubClass->expenditureClass->expenditure_class_id;
                $new_budgetallocationitem->expenditure_subclass_id = $expenditure_object->expenditure_sub_class_id;
                $new_budgetallocationitem->category_id = $expenditure_object->expenditure_object_id;
                $new_budgetallocationitem->budget_allocation_id = $model->destination_id;
                $new_budgetallocationitem->name = $expenditure_object->name;
                $new_budgetallocationitem->amount = $model->amount;
                $new_budgetallocationitem->active = 1;
                $new_budgetallocationitem->save(false);
            }
            
            $model->create_date   = new Expression('NOW()');
            $model->created_by = Yii::$app->user->identity->user_id;
            
            $modelDeduction->item_id = $model->source_item;
            $modelDeduction->item_detail_id = $model->item_detail_id;
            $modelDeduction->source_item = 0;
            $modelDeduction->amount = - $model->amount;
            $modelDeduction->create_date   = new Expression('NOW()');
            $modelDeduction->created_by = Yii::$app->user->identity->user_id;

            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->save(false);
                $modelDeduction->save(false);

                $transaction->commit();
                
                Yii::$app->session->setFlash('success', "Allocation successful.");
                return $this->redirect(['/budget/budgetallocation/view', 'id' => $_GET['id']]);
                
            } catch(\Exception $e) {
                $transaction->rollBack();
                //throw $e;
                
                Yii::$app->session->setFlash('failure', $e);
                return $this->redirect(['/budget/budgetallocation/view', 'id' => $_GET['id']]);
            }
            
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'budgetallocationitem'=>$budgetallocationitem,
                        'listSections'=>$listSections,
                        'sections'=>$sections,
                        'budgetallocationitems'=>$budgetallocationitems,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'budgetallocationitem'=>$budgetallocationitem,
                        'listSections'=>$listSections,
                        'sections'=>$sections,
                        'budgetallocationitems'=>$budgetallocationitems,
            ]);
        }
    }

    /**
     * Updates an existing Allocationadjustment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->allocation_adjustment_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Allocationadjustment model.
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
     * Finds the Allocationadjustment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Allocationadjustment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Allocationadjustment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionExpenditures()
    {
        if(isset($_POST['id']))
            $id = $_POST['id'];
        
        if(isset($_POST['itemid']))
            $itemid = $_POST['itemid'];
        
        /*if(isset($_POST['budgetAllocationId']))
            $budgetAllocationId = $_POST['budgetAllocationId'];
        
        if(isset($_POST['budgetAllocationItemId']))
            $budgetAllocationItemId = $_POST['budgetAllocationItemId'];
        
        if(isset($_POST['sourceId']))
            $sourceId = $_POST['sourceId'];*/
        
        $searchModel = new ExpenditureobjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        

        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_allocations', [
                'dataProvider'=>$dataProvider,
                'id'=>$id,
                //'itemid'=>$itemid,
                //'budgetAllocationId'=>$budgetAllocationId,
                //'budgetAllocationItemId'=>$budgetAllocationItemId,
                //'sourceId'=>$sourceId
            ]);
        }
        else{
            return $this->render('_allocations', ['dataProvider'=>$dataProvider]);
        }
    }
}
