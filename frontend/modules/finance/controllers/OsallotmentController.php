<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\Os;
use common\models\finance\Osallotment;
use common\models\finance\OsallotmentSearch;
use common\models\finance\Osdv;
use common\models\procurement\Expenditureobject;
use common\models\procurement\ExpenditureobjectSearch;
use common\models\procurement\Expenditureobjecttype;
use common\models\procurement\Expenditureobjectsubtype;

use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * OsallotmentController implements the CRUD actions for Osallotment model.
 */
class OsallotmentController extends Controller
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
     * Lists all Osallotment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OsallotmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionIndex2()
    {
//        $id = $_GET['id'];
//        $year = $_GET['year'];
//        $id = $_GET['id'];
//        $year = $_GET['year'];
        $searchModel = new ExpenditureobjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index2', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        //'id' => $id,
                        //'year' => $year,
            ]);
        } else {
            return $this->render('index2', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        //'id' => $id,
                        //'year' => $year,
            ]);
        }
    }

    /**
     * Displays a single Osallotment model.
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
     * Creates a new Osallotment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Osallotment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->os_allotment_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Osallotment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->os_allotment_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Osallotment model.
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
     * Finds the Osallotment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Osallotment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Osallotment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
        $osallotment = Osallotment::find()->where([
                                    'osdv_id' => $id, 'expenditure_object_id' => $objectid, 
                                    //'year' => $year
                                        ])->one();
        
        if($osallotment)
        {
            //$out = 'chene - '. $_POST['checked']. ' - '.$_POST['year'].' - '.$objectid;
            if($checked == 'true'){
                $osallotment->active = 1;
                $osallotment->save(false);
            }
            else{
                $osallotment->active = 0;
                $osallotment->save(false);
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
            $model = new Osallotment();
            $model->expenditure_class_id = $expenditure_object->expenditureSubClass->expenditureClass->expenditure_class_id;
            //$model->expenditure_subclass_id = $expenditure_object->expenditure_sub_class_id;
            $model->expenditure_object_id = $expenditure_object->expenditure_object_id;
            $model->osdv_id  = $id;
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
           $qty = $_POST['Osallotment'][$index][$attr];
           $model = Osallotment::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
           else
               return false;
       }
    }
    
    public function actionAddfinancialsubsidy()
    {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        
        if($model->load(Yii::$app->request->post())) {
            
            $data = Yii::$app->request->post();
            $model->object_type_id = $data['Osallotment']['object_type_id'];
            $model->object_sub_type_id = !isset($data['Osallotment']['object_sub_type_id']) ? 0 : $data['Osallotment']['object_sub_type_id'];
            if($model->save(false)){
 
                Yii::$app->session->setFlash('success', 'Financial Subsidy Successfully Applied!');
                return $this->redirect(['/finance/osdv/view', 'id' => $model->osdv_id]);
            }else{
                Yii::$app->session->setFlash('warning', $model->getErrors());                 
            }
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_financialsubsidy', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_financialsubsidy', [ 'model' => $model, ]);
        }
    }
    
    public function actionListobjects() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Expenditureobjectsubtype::find()->andWhere(['expenditure_object_type_id'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $unit) {
                    $out[] = ['id' => $unit['expenditure_object_sub_type_id'], 'name' => $unit['name']];
                    if ($i == 0) {
                        $selected = $unit['expenditure_object_sub_type_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }
    
    public function actionUpdateuacsforobjecttype()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $response = Expenditureobjecttype::findOne($_POST['objectTypeId']);
        if($response)
            return $response;
    }
    
    public function actionUpdateuacsforobjectsubtype()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $response = Expenditureobjectsubtype::findOne($_POST['objectSubTypeId']);
        if($response)
            return $response;
    }

    public function fetchdata(){
        $response = [];

        $oss = Os::find()
            ->where(['YEAR(`os_date`)' => 2023])
            ->andWhere(['deleted' => 0])
            // ->offset(10)
            // ->limit(10)
            ->all();

        $index = 0;
        foreach($oss as $os){
            
            array_push($response, [
                    '1' => $os->os_date, //Date
                    '2' => $os->os_number, //OS Number
                    '3' => $os->request->creditor->name, //Payee
                    '4' => $os->request->particulars, //Particulars
                    '7' => $os->request->amount,
                    '5010101001' => '',
                    '5010201001' => '',
                    '5010202000' => '',
                    '5010203000' => '',
                    '5010204001' => '',
                    '5010214001' => '',
                    '5010215001' => '',
                    '5010000000' => '',
                    '5010302001' => '',
                    '5010303001' => '',
                    '5010304001' => '',
                    '5010205002' => '',
                    '5010206003' => '',
                    '5010211004' => '',
                    '5010212003' => '',
                    '5010301000' => '',
                    '5010499000' => '',
                    '5020000000' => '',
                    '5020101000' => '',
                    '5020102000' => '',
                    '5020201000' => '',
                    '5020301000' => '',
                    '5020302000' => '',
                    '5020308000' => '',
                    '5020309000' => '',
                    '5020310000' => '',
                    '5020311001' => '',
                    '5020399000' => '',
                    '5020401000' => '',
                    '5020402000' => '',
                    '5020501000' => '',
                    '5020502002' => '',
                    '5020502001' => '',
                    '5020504000' => '',
                    '5021003000' => '',
                    '5021101000' => '',
                    '5021102000' => '',
                    '5021103000' => '',
                    '5021202000' => '',
                    '5021203000' => '',
                    '5021299000' => '',
                    '5021304000' => '',
                    '5021305000' => '',
                    '5021305000' => '',
                    '5021306000' => '',
                    '5021306000' => '',
                    '5021402000' => '',
                    '5021403000' => '',
                    '5021499000' => '',
                    '5021501000' => '',
                    '5021502000' => '',
                    '5021503000' => '',
                    '5029901000' => '',
                    '5029902000' => '',
                    '5029903000' => '',
                    '5029905001' => '',
                    '5029905003' => '',
                    '5029905005' => '',
                    '5029906000' => '',
                    '5029907000' => '',
                    '5029999000' => '',
                    '5060000000' => '',
                    '1080102000' => '',
                    // ONE EXPERT 
                    // IE	
                    // MetroLab
                    // ONELAB I	
                    // CEST
                    // ONELAB II
                    // MetroLab
                    // CBFEWS
                    // ONELAB II
                ]
            );
            $alloments = Osallotment::find()->Where(['osdv_id' => $os->osdv_id, 'active' => 1])->all();
            // $os_allotment = [];

            foreach($alloments as $alloment){
                // 5021499000 //Subsidies - Others / Financial Assitance to LGUs / Local GIA / SETUP
                $response[$index][strval($alloment->expenditureobject->object_code)] = $alloment->amount;
            }
            $index += 1;
        }

        return $response;
    }

    public function actionObligationdata()
    {
        $response = $this->fetchdata();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if($response)
            return $response;
    }

    public function actionViewobligation()
    {
        
        $dataProvider = $this->fetchdata();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataProvider,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('viewobligation', [
            'dataProvider' => $dataProvider,
        ]);
    }
    

    function actionObligationdata2(){

            return '{
                "response":{"Date":1494,"start":1,"docs":[
                    {
                      "id":"10.1371/journal.pone.0188133"},
                    {
                      "id":"10.1371/journal.pbio.1000320"},
                    {
                      "id":"10.1371/journal.pbio.0000060"},
                    {
                      "id":"10.1371/journal.pbio.3000094"}
                      ]
                }}';
    }
    
}
