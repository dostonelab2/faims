<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurement\Division;
use common\models\procurement\Section;
use common\models\procurement\Unit;

use common\models\procurementplan\Item;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\PpmpSearch;
use common\models\procurementplan\PpmpitemSearch;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * PpmpController implements the CRUD actions for Ppmp model.
 */
class AppController extends Controller
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
     * Lists all Ppmp models.
     * @return mixed
     */
    public function actionView()
    {
        if(isset($_GET['year']))
            $selected_year = $_GET['year'];
        else 
            $selected_year = '2019';
        //$selected_year = isset($_POST['year']) ? $_POST['year'] : '';
        
        $searchModel = new PpmpSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $sections = Section::find()->orderBy('division_id, section_id');
        $dataProvider = Ppmp::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $dataProvider,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    
        
        $ppmpDataProvider = new ActiveDataProvider([
            'query' => $sections,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

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
        ]);
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ppmpDataProvider' => $ppmpDataProvider,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            'listDivisions' => $listDivisions,
            
            'listUnits' => $listUnits,
            'selected_year' => $selected_year,
        ]);
    }
    
    public function actionIndex2()
    {

        $queryPpmpItems = Ppmpitem::find()->select([
            'ppmp_item_id' => 'tbl_ppmp_item.ppmp_item_id',
            'ppmp_id' => 'tbl_ppmp_item.ppmp_id',
            'item_id' => 'tbl_ppmp_item.item_id',
            'item_category_id' => 'tbl_ppmp_item.item_category_id',
            'ppmp_item_category_id' => 'tbl_ppmp_item.ppmp_item_category_id',
            'code' => 'tbl_ppmp_item.code',
            'description' => 'tbl_ppmp_item.description',
            'item_specification' => 'tbl_ppmp_item.item_specification',
            'quantity' => '(sum(tbl_ppmp_item.q1) + sum(tbl_ppmp_item.q2) + sum(tbl_ppmp_item.q3) + sum(tbl_ppmp_item.q4) + sum(tbl_ppmp_item.q5) + sum(tbl_ppmp_item.q6) + sum(tbl_ppmp_item.q7) + sum(tbl_ppmp_item.q8) + sum(tbl_ppmp_item.q9) + sum(tbl_ppmp_item.q10) + sum(tbl_ppmp_item.q11) + sum(tbl_ppmp_item.q12))',
            'unit' => 'tbl_ppmp_item.unit',
            'cost' => 'tbl_ppmp_item.cost',
            'estimated_budget' => 'tbl_ppmp_item.estimated_budget',
            'mode_of_procurement' => 'tbl_ppmp_item.mode_of_procurement',
            'availability' => 'tbl_ppmp_item.availability',
            'jan' => 'sum(tbl_ppmp_item.q1)',
            'feb' => 'sum(tbl_ppmp_item.q2)',
            'mar' => 'sum(tbl_ppmp_item.q3)',
            'q1' => '(sum(tbl_ppmp_item.q1) + sum(tbl_ppmp_item.q2) + sum(tbl_ppmp_item.q3))',
            'q1amount' => '(sum(tbl_ppmp_item.q1) + sum(tbl_ppmp_item.q2) + sum(tbl_ppmp_item.q3)) * cost',
            'apr' => 'sum(tbl_ppmp_item.q4)',
            'may' => 'sum(tbl_ppmp_item.q5)',
            'jun' => 'sum(tbl_ppmp_item.q6)',
            'q2' => '(sum(tbl_ppmp_item.q4) + sum(q5) + sum(q6))',
            'q2amount' => '(sum(q4) + sum(q5) + sum(q6)) * cost',
            'jul' => 'sum(tbl_ppmp_item.q7)',
            'aug' => 'sum(tbl_ppmp_item.q8)',
            'sep' => 'sum(tbl_ppmp_item.q9)',
            'q3' => '(sum(tbl_ppmp_item.q7) + sum(tbl_ppmp_item.q8) + sum(tbl_ppmp_item.q9))',
            'q3amount' => '(sum(tbl_ppmp_item.q7) + sum(tbl_ppmp_item.q8) + sum(tbl_ppmp_item.q9)) * cost',
            'oct' => 'sum(tbl_ppmp_item.q10)',
            'nov' => 'sum(tbl_ppmp_item.q11)',
            'dec' => 'sum(tbl_ppmp_item.q12)',
            'q4' => '(sum(tbl_ppmp_item.q10) + sum(tbl_ppmp_item.q11) + sum(tbl_ppmp_item.q12))',
            'q4amount' => '(sum(tbl_ppmp_item.q10) + sum(tbl_ppmp_item.q11) + sum(tbl_ppmp_item.q12)) * cost',
            'month' => 'tbl_ppmp_item.month',
            'active' => 'tbl_ppmp_item.active',
            'status_id' => 'tbl_ppmp_item.status_id',
            'supplemental' => 'tbl_ppmp_item.supplemental',
            'year' => 'tbl_ppmp.year',
            'totalamount' => '(sum(tbl_ppmp_item.q1) + sum(tbl_ppmp_item.q2) + sum(tbl_ppmp_item.q3) + sum(tbl_ppmp_item.q4) + sum(tbl_ppmp_item.q5) + sum(tbl_ppmp_item.q6) + sum(tbl_ppmp_item.q7) + sum(tbl_ppmp_item.q8) + sum(tbl_ppmp_item.q9) + sum(tbl_ppmp_item.q10) + sum(tbl_ppmp_item.q11) + sum(tbl_ppmp_item.q12)) * cost'

        ])
                         
                            ->groupBy('tbl_ppmp_item.item_id')
                            ->joinWith('ppmp');

        if(Yii::$app->request->isAjax){
            $year = $_GET['year'];
            $queryPpmpItems->where([
                                'tbl_ppmp_item.active' => 1,
                                'tbl_ppmp_item.status_id' => 2,
                                'tbl_ppmp.year' => $year]);
            $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                    ]
                ],
            ]);
                return $this->render('_index', [
                'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
                'isMember' => false
            ]);
        }else{
            $queryPpmpItems->where([
                                'tbl_ppmp_item.active' => 1,
                                'tbl_ppmp_item.status_id' => 2]);
            $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                    ]
                ],
            ]);
                return $this->render('_index', [
                'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
                'isMember' => false
            ]);
        }
    }
    public function actionIndex()
    {
        $searchModel = new PpmpitemSearch();
        $ppmpItemsDataProvider = $searchModel->appsearch(Yii::$app->request->queryParams);
        return $this->render('_index', [
                'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
                'searchModel' => $searchModel
            ]);
    }
}
