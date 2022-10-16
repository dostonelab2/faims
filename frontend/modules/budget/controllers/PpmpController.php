<?php

namespace frontend\modules\budget\controllers;

use Yii;
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

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class PpmpController extends Controller
{
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }

    public function actionIndex()
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
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ppmpDataProvider' => $ppmpDataProvider,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            'listDivisions' => $listDivisions,
            
            'listUnits' => $listUnits,
            'selected_year' => $selected_year,
        ]);
    }
    
    public function actionView($id)
    {
        //$queryPpmpItems = Ppmpitem::find();
        $model = Ppmp::findOne($id);
        $isMember = $model->isMember();
        $disableApprovePpmp = ($model->status_id == 1) ? true : false;
        $disableAddItem = ($model->status_id != 1) ? true : false;

        $queryPpmpItems = Ppmpitem::find()->where([
                                    'ppmp_id' => $id, 
                                    'active' => 1]);
        
        $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        return $this->render('view', [
            'model' => $model,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            'disableApprovePpmp' => $disableApprovePpmp,
            'disableAddItem' => $disableAddItem,
            'isMember' => $isMember
        ]);
    }
}
