<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurement\Division;
use common\models\procurement\Section;
use common\models\procurement\Project;
use common\models\procurement\ProjectSearch;
use common\models\procurement\Unit;

use common\models\procurementplan\Item;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\PpmpSearch;
use common\models\budget\Budgetallocation;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\db\Expression;
use yii\web\HttpException;
/**
 * PpmpController implements the CRUD actions for Ppmp model.
 */
class PpmpController extends Controller
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
    public function actionIndex()
    {
        if(isset($_GET['year']))
            $selected_year = $_GET['year'];
        else 
            $selected_year =  date('Y');
        //$selected_year = isset($_POST['year']) ? $_POST['year'] : '';
        
        $searchModel = new PpmpSearch();
        $searchProjectModel = new ProjectSearch();
        $searchSectionModel = new Section();
        
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $sections = Section::find()->orderBy('division_id, section_id')->joinWith('budgetallocation');
        $dataProvider = Ppmp::find();
        
        $ppmpDataProvider = new ActiveDataProvider([
            'query' => $sections,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

       
        $projectQuery = Project::find()->joinWith('budgetallocation');

        
        $projectDataProvider = new ActiveDataProvider([
            'query' => $projectQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'searchProjectModel' => $searchProjectModel,
            'dataProvider' => $dataProvider,
            'ppmpDataProvider' => $ppmpDataProvider,
            'projectDataProvider' => $projectDataProvider,
            'listDivisions' => $listDivisions,
            'searchSectionModel' => $searchSectionModel,
            'listUnits' => $listUnits,
            'selected_year' => $selected_year,
        ]);
    }

    /**
     * Displays a single Ppmp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //$queryPpmpItems = Ppmpitem::find();
        $model = $this->findModel($id);
        $isMember = $model->isMember();
        //$disableSubmitPpmp = ($model->status_id != 1) ? true : false;
        //$status = ($model->status_id != 1) ? true : false;

        /*$queryPpmpItems = Ppmpitem::find()->where([
                                    'ppmp_id' => $id, 
                                    'active' => 1,
                                    'supplemental' => 0,]);*/
        $queryPpmpItems = Ppmpitem::find()->select([
            'ppmp_item_id' => 'tbl_ppmp_item.ppmp_item_id',
            'ppmp_id' => 'tbl_ppmp_item.ppmp_id',
            'item_id' => 'tbl_ppmp_item.item_id',
            'availability' => 'tbl_ppmp_item.availability',
            'item_category_id' => 'tbl_ppmp_item.item_category_id',
            'description' => 'tbl_ppmp_item.description',
            'unit' => 'tbl_ppmp_item.unit',
            'cost' => 'tbl_ppmp_item.cost',
            'q1' => 'SUM(tbl_ppmp_item.q1)',
            'q2' => 'SUM(tbl_ppmp_item.q2)',
            'q3' => 'SUM(tbl_ppmp_item.q3)',
            'q4' => 'SUM(tbl_ppmp_item.q4)',
            'q5' => 'SUM(tbl_ppmp_item.q5)',
            'q6' => 'SUM(tbl_ppmp_item.q6)',
            'q7' => 'SUM(tbl_ppmp_item.q7)',
            'q8' => 'SUM(tbl_ppmp_item.q8)',
            'q9' => 'SUM(tbl_ppmp_item.q9)',
            'q10' => 'SUM(tbl_ppmp_item.q10)',
            'q11' => 'SUM(tbl_ppmp_item.q11)',
            'q12' => 'SUM(tbl_ppmp_item.q12)',
            'quantity' => 'tbl_ppmp_item.quantity',
            'estimated_budget' => 'tbl_ppmp_item.estimated_budget',
            'ppmp_status_id' => 'tbl_ppmp.status_id',
            'active' => 'tbl_ppmp_item.active',
            'supplemental' => 'tbl_ppmp_item.supplemental',
            'status_id' => 'tbl_ppmp_item.status_id'])->where([
                         'tbl_ppmp_item.ppmp_id' => $id, 
                         'tbl_ppmp_item.active' => 1,])->groupBy([
                                    'tbl_ppmp_item.item_id',
                                    'tbl_ppmp_item.ppmp_id'])->joinWith(['ppmp']);

        $supplemental = Ppmpitem::find()->where([
                                    'ppmp_id' => $id, 
                                    'active' => 1,
                                    'supplemental' => 1]);
        //$ppmp = Ppmp::find()->where(['ppmp_id' => $id])->one();

        $supplementalDataProvider = new ActiveDataProvider([
            'query' => $supplemental,
        ]);
        
        $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        return $this->render('view', [
            'model' => $model,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            //'status' => $status,
            'isMember' => $isMember,
            'supplementalDataProvider' => $supplementalDataProvider,
            //'ppmp' => $ppmp,
        ]);
    }
    
    public function actionCreate() 
    {
        $model = new Ppmp();
        
        $listYear = ['2019' => '2019', '2018' => '2018'];
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string) $model->ppmp_id]);
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'listDivisions' => $listDivisions,
                        'listUnits' => $listUnits,
                        'listYear' => $listYear,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'listDivisions' => $listDivisions,
                        'listUnits' => $listUnits,
                        'listYear' => $listYear,
            ]);
        }
    }

    /**
     * Updates an existing Ppmp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ppmp_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ppmp model.
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
     * Finds the Ppmp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ppmp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ppmp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAdditems()
    {
        /** Post Data
            itemId : 4
            checked : true
            ppmpId : 2
            year : 2019
        **/
        $ppmpId = $_POST['ppmpId'];
        $itemId = $_POST['itemId'];
        $checked = $_POST['checked'];
        $year = $_POST['year'];

        
        $item = Item::findOne($itemId);
        $ppmp_item = Ppmpitem::find()->where([
                                    'ppmp_id' => $ppmpId, 
                                    'item_id' => $itemId])->one();
        
        if($ppmp_item)
        {
            //echo Json::encode(['message'=>$ppmp_item]);
            if($checked == 'true'){
                $ppmp_item->active = 1;
                $ppmp_item->save(false);
            }
            else{
                $ppmp_item->active = 0;
                $ppmp_item->save(false);
            }
        }else{
            $model = new Ppmpitem();
        
            $model->ppmp_id = $ppmpId;
            $model->item_id = $item->item_id;
            $model->item_category_id = $item->item_category_id;
            $model->code = $item->item_code;
            $model->description = $item->item_name;
            $model->unit = $item->unit_of_measure_id ? $item->unit_of_measure_id : 18;
            $model->cost = $item->price_catalogue;
            $model->availability = $item->availability;
            $model->active = 1;
            $model->save(false);
        }
        
        echo Json::encode(['message'=>$ppmpId]);
    }

    public function actionUpdateqty() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Ppmpitem'][$index][$attr];
           $model = Ppmpitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
               //echo Yii::$app->session->setFlash('success', "Quantity updated.");
           else
               return false;
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }
    
    public function actionSubmit($id)
    {
        $ppmp = Ppmp::find()->where(['ppmp_id' => $id])->one();
        //$ppmpitem = Ppmpitem::updateAll(['status_id' => 1],['ppmp_id' => $id]);
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post('status');
            $ppmp->status_id = $data;
            $ppmp->submitted_date = new Expression('NOW()');
            $ppmp->submitted_user = Yii::$app->user->id;
            if($data){
                    if($ppmp->save(false)){
                        //$ppmpitem;
                        Yii::$app->session->setFlash('success', "PPMP successfully submitted.");
                        return $this->redirect(['view', 'id' => (string) $ppmp->ppmp_id]);
                    }
               }
      
        }else{
            throw new HttpException(403, 'You are not allowed to perform this action.');
        }
        /*
        $model = $this->findModel($_GET['id']);
        if (Yii::$app->request->isAjax) {
            if(!$model->isMember())
            {
                return $this->renderAjax('_info', ['message'=>'You are not Authorized to do this action.']);   
            }elseif($model->status_id == Ppmp::STATUS_SUBMITTED){
                return $this->renderAjax('_info', ['message'=>'This PPMP has been submitted for Approval.']);   
            }
        }
        if (Yii::$app->request->post()) {
            $model->status_id = 2;
            if($model->save(false)){
                Yii::$app->session->setFlash('success', "PPMP successfully submitted.");
                return $this->redirect(['view', 'id' => (string) $model->ppmp_id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_submit', [
                        'model' => $model,
            ]);
        }*/ 
    }

    public function actionApproved($id)
    {
        //$model = $this->findModel($_GET['id']);
        $model = Ppmp::find()->where(['ppmp_id' => $id])->one();
        $ppmpitemupdateall = Ppmpitem::updateAll(['status_id' => 2],['ppmp_id' => $id, 'supplemental' => 0]);
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post('status');
            $model->status_id = $data;
            $model->approved_date = new Expression('NOW()');
            $model->approved_user = Yii::$app->user->id;
            if($data){
                   if($model->save(false)){
                        $ppmpitemupdateall;
                        Yii::$app->session->setFlash('success', "PPMP successfully approved.");
                        return $this->redirect(['view', 'id' => (string) $model->ppmp_id]);
                }
            }
         
        }else{
                throw new HttpException(403, 'You are not allowed to perform this action.');
        }
    }
    /*
    public function actionSupplementalitem($id)

    {
        $model = $this->findModel($id);
        $query = Ppmpitem::find()
                    ->where(['ppmp_id' => $id,'supplemental' => true]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,],
        ]);

        return $this->render('supplementalitem', [

            'dataProvider' => $dataProvider,
            'model' => $model,

        ]);
    }*/

    public function actionAddsupplementalitems()
    {
        $ppmpId = $_POST['ppmpId'];
        $itemId = $_POST['itemId'];
        $checked = $_POST['checked'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $userid = Yii::$app->user->id;

        
        $item = Item::findOne($itemId);
        $ppmp_item = Ppmpitem::find()->where([
                                    'ppmp_id' => $ppmpId, 
                                    'item_id' => $itemId,
                                    'status_id' => 0,
                                    'month' => $month])->one();
        
        if($ppmp_item)
        {
            //echo Json::encode(['message'=>$ppmp_item]);
            if($checked == 'true'){
                $ppmp_item->active = 1;
                $ppmp_item->create_date = new Expression('NOW()');
                $ppmp_item->create_user = $userid;
                $ppmp_item->save(false);

            }
            else{
                $ppmp_item->active = 0;
                $ppmp_item->create_date = NULL;
                $ppmp_item->create_user = NULL;
                $ppmp_item->save(false);

            }
        }else{
            $model = new Ppmpitem();
        
            $model->ppmp_id = $ppmpId;
            $model->item_id = $item->item_id;
            $model->item_category_id = $item->item_category_id;
            $model->code = $item->item_code;
            $model->description = $item->item_name;
            $model->unit = $item->unit_of_measure_id ? $item->unit_of_measure_id : 18;
            $model->cost = $item->price_catalogue;
            $model->availability = $item->availability;
            $model->month = $month;
            $model->supplemental = 1;
            $model->active = 1;
            $model->create_date = new Expression('NOW()');
            $model->create_user = $userid;
            $model->save(false);
        }
        
        echo Json::encode(['message'=>$ppmpId]);
    }
}
