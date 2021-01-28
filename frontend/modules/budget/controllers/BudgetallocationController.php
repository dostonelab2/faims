<?php

namespace frontend\modules\budget\controllers;

use Yii;
use common\models\budget\Budgetallocation;
use common\models\budget\Budgetallocationitem;
use common\models\budget\BudgetallocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\procurement\Division;
use common\models\procurement\Section;
use common\models\procurement\Unit;

use common\models\procurementplan\Item;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\PpmpSearch;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * BudgetallocationController implements the CRUD actions for Budgetallocation model.
 */
class BudgetallocationController extends Controller
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
    public function actionNep()
    {
        
        
        return $this->render('nep', [
            'dataProvider' => $dataProvider,
            'listUnits' => $listUnits,
            'selected_year' => $selected_year,
        ]);
    }
    /**
     * Lists all Budgetallocation models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $searchModel = new BudgetallocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/
    public function actionIndex()
    {
        if(isset($_GET['year']))
            $selected_year = $_GET['year'];
        else 
            $selected_year = '2019';
        //$selected_year = isset($_POST['year']) ? $_POST['year'] : '';
        
        $searchModel = new BudgetallocationSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        //$divisions = Division::find()->orderBy('name')->asArray()->all();
        //$listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $sections = Section::find()->where(['>', 'section_id', 0])->orderBy('division_id, section_id');
        /*$dataProvider = Ppmp::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $dataProvider,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);*/
    
        
        $sectionsDataProvider = new ActiveDataProvider([
            'query' => $sections,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        /*
        $queryPpmpItems = Ppmpitem::find();
        $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    //'created_at' => SORT_DESC,
                    //'title' => SORT_ASC, 
                ]
            ],
        ]);*/
        
        //$units = Unit::find()->orderBy('name')->asArray()->all();
        //$listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'sectionsDataProvider' => $sectionsDataProvider,
            //'listDivisions' => $listDivisions,
            
            //'listUnits' => $listUnits,
            'selected_year' => $selected_year,
        ]);
    }

    /**
     * Displays a single Budgetallocation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //$queryBudgetAllocationItems = Budgetallocationitem::find()
                                        //->select(['budget_allocation_item_id', 'budget_allocation_id', 'name', 'code', 'category_id', 'amount', ])
                                        //->where(['budget_allocation_id' => $id])
                                        //->groupBy('expenditure_class_id');
        
        //$queryBudgetAllocationItems = $model->getItems();
            //->groupBy('expenditure_class_id');
       
        
      
        $budgetAllocationItemsDataProvider = new ActiveDataProvider([
            'query' => $model->getItems(),
            //'query' => $model->getItems(),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'expenditure_subclass_id' => SORT_ASC,
                    'budget_allocation_item_id' => SORT_ASC,
                    'name' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('view', [
            'model' => $model,
            'budgetAllocationItemsDataProvider' => $budgetAllocationItemsDataProvider,
            'year' => $model->year,

        ]);
    }

    /**
     * Creates a new Budgetallocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Budgetallocation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->budget_allocation_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Budgetallocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->budget_allocation_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Budgetallocation model.
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
     * Finds the Budgetallocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Budgetallocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Budgetallocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAdjustment() //call to add expenditure
    {
        //$model = new Itemadjustment();
        $item_id = $_GET['itemid'];
        //$expenditure_object = Expenditureobject::findOne($objectid);
        $budgetallocationitem = Budgetallocationitem::find()->where([
                                    'budget_allocation_item_id' => $item_id, //'category_id' => $objectid, 
                                    //'year' => $year
                                        ])->one();
        
        
        
        if (Yii::$app->request->post()) {

        }

        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_adjustment', [
                    'budgetallocationitem'=>$budgetallocationitem
                ]);   
        }else {
            return $this->render('_adjustment', [
                    'budgetallocationitem'=>$budgetallocationitem
            ]);
        }
        

            /*$model = new Budgetallocationitem();
            $model->expenditure_class_id = $expenditure_object->expenditureSubClass->expenditureClass->expenditure_class_id;
            $model->expenditure_subclass_id = $expenditure_object->expenditure_sub_class_id;
            $model->category_id = $expenditure_object->expenditure_object_id;
            $model->budget_allocation_id = $id;
            $model->name = $expenditure_object->name;
            $model->active = 1;
            $model->save(false);*/
    
        //echo Json::encode(['message'=>$out]);
    }
    
    public function actionAdjustmenthistory($year, $section = NULL)
    {
        $adjustments = AllocationadjustmentSearch::find();
        
        //=  Allocationadjustment::find()->where(['YEAR(create_date)' => $year])->andWhere(['>','status_id',0])->with(['samples' => function($query){
        //        $query->andWhere(['active'=>'1']);
        //    }])->all();
    }
}
