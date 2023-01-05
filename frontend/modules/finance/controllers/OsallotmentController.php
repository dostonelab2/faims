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

    public function actionObligationdata()
    {
        $response = [];

        /*$osdvs = Osdv::find()
        ->where(['YEAR(`create_date`)' => 2023])
        ->andWhere(['type_id' => 1])
        ->andWhere(['cancelled' => 0])
        ->andWhere(['>=','status_id', 40])
        ->limit(100)
        ->all();*/

        $oss = Os::find()
            ->where(['YEAR(`os_date`)' => 2023])
            ->andWhere(['deleted' => 0])
            ->limit(10)
            ->all();

        $index = 0;

        foreach($oss as $os){
            $response["response"] = [];
            array_push($response["response"], 
                [$os->osdv_id, $os->os_date, $os->os_number, $os->request->creditor->name, $os->request->particulars, $os->request->amount]
            );

            $alloments = Osallotment::find()->Where(['osdv_id' => $os->osdv_id, 'active' => 1])->all();
            $os_allotment = [];
            $os_index = 0;
            foreach($alloments as $alloment){
                $os_allotment[$os_index] = $alloment->amount;
                $os_index += 1;
            }
            array_push($response["response"][$index], $os_allotment);
            $index += 1;
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if($response)
            return $response;
    }

    function actionObligationdata2(){

        return '{
            "date":{"numFound":1494,"start":1,"docs":[
                {
                  "id":"10.1371/journal.pone.0188133"},
                {
                  "id":"10.1371/journal.pbio.1000320"},
                {
                  "id":"10.1371/journal.pbio.0000060"},
                {
                  "id":"10.1371/journal.pone.0001265"},
                {
                  "id":"10.1371/journal.pcbi.1000026"},
                {
                  "id":"10.1371/journal.pgen.1002593"},
                {
                  "id":"10.1371/journal.pgen.1009790"},
                {
                  "id":"10.1371/journal.pgen.1009702"},
                {
                  "id":"10.1371/journal.pgen.1010024"},
                {
                  "id":"10.1371/journal.pone.0012421"},
                {
                  "id":"10.1371/journal.pgen.1002564"},
                {
                  "id":"10.1371/journal.pgen.1008251"},
                {
                  "id":"10.1371/journal.pone.0201609"},
                {
                  "id":"10.1371/journal.pone.0020371"},
                {
                  "id":"10.1371/journal.pone.0177408"},
                {
                  "id":"10.1371/journal.pone.0008928"},
                {
                  "id":"10.1371/journal.pone.0190367"},
                {
                  "id":"10.1371/journal.pgen.1000681"},
                {
                  "id":"10.1371/journal.pgen.1000880"},
                {
                  "id":"10.1371/journal.pone.0002506"},
                {
                  "id":"10.1371/journal.pone.0004669"},
                {
                  "id":"10.1371/journal.pgen.0020005"},
                {
                  "id":"10.1371/journal.pone.0092956"},
                {
                  "id":"10.1371/journal.pgen.1010005"},
                {
                  "id":"10.1371/journal.pgen.1006022"},
                {
                  "id":"10.1371/journal.pone.0083547"},
                {
                  "id":"10.1371/journal.ppat.1003808"},
                {
                  "id":"10.1371/journal.pone.0003041"},
                {
                  "id":"10.1371/journal.pone.0011115"},
                {
                  "id":"10.1371/journal.pgen.1004844"},
                {
                  "id":"10.1371/journal.pgen.1003013"},
                {
                  "id":"10.1371/journal.pgen.1009003"},
                {
                  "id":"10.1371/journal.pone.0011498"},
                {
                  "id":"10.1371/journal.pone.0138569"},
                {
                  "id":"10.1371/journal.pone.0240596"},
                {
                  "id":"10.1371/journal.pone.0055915"},
                {
                  "id":"10.1371/journal.pone.0001595"},
                {
                  "id":"10.1371/journal.pbio.1002210"},
                {
                  "id":"10.1371/journal.pgen.1006931"},
                {
                  "id":"10.1371/journal.pbio.1000335"},
                {
                  "id":"10.1371/journal.pgen.1006929"},
                {
                  "id":"10.1371/journal.pone.0008549"},
                {
                  "id":"10.1371/journal.pone.0098173"},
                {
                  "id":"10.1371/journal.pbio.3000689"},
                {
                  "id":"10.1371/journal.pone.0016562"},
                {
                  "id":"10.1371/journal.pone.0006478"},
                {
                  "id":"10.1371/journal.pone.0195845"},
                {
                  "id":"10.1371/journal.pbio.3000241"},
                {
                  "id":"10.1371/journal.pgen.1005867"},
                {
                  "id":"10.1371/journal.pgen.1004455"},
                {
                  "id":"10.1371/journal.pgen.1009108"},
                {
                  "id":"10.1371/journal.pbio.1001428"},
                {
                  "id":"10.1371/journal.pone.0001613"},
                {
                  "id":"10.1371/journal.pone.0083942"},
                {
                  "id":"10.1371/journal.pone.0193612"},
                {
                  "id":"10.1371/journal.pgen.1007496"},
                {
                  "id":"10.1371/journal.pgen.1006648"},
                {
                  "id":"10.1371/journal.pgen.1000814"},
                {
                  "id":"10.1371/journal.pgen.1005406"},
                {
                  "id":"10.1371/journal.pone.0036059"},
                {
                  "id":"10.1371/journal.pbio.0000013"},
                {
                  "id":"10.1371/journal.pone.0189772"},
                {
                  "id":"10.1371/journal.pone.0057214"},
                {
                  "id":"10.1371/journal.pone.0133956"},
                {
                  "id":"10.1371/journal.pone.0015361"},
                {
                  "id":"10.1371/journal.pgen.1007016"},
                {
                  "id":"10.1371/journal.pgen.0030076"},
                {
                  "id":"10.1371/journal.pone.0177710"},
                {
                  "id":"10.1371/journal.pone.0244701"},
                {
                  "id":"10.1371/journal.pone.0103564"},
                {
                  "id":"10.1371/journal.pone.0000476"},
                {
                  "id":"10.1371/journal.pone.0007436"},
                {
                  "id":"10.1371/journal.pgen.1009425"},
                {
                  "id":"10.1371/journal.pgen.1005761"},
                {
                  "id":"10.1371/journal.pone.0022218"},
                {
                  "id":"10.1371/journal.pgen.1006100"},
                {
                  "id":"10.1371/journal.ppat.1004256"},
                {
                  "id":"10.1371/journal.pone.0007008"},
                {
                  "id":"10.1371/journal.pone.0176689"},
                {
                  "id":"10.1371/journal.pgen.1006782"},
                {
                  "id":"10.1371/journal.pgen.1009229"},
                {
                  "id":"10.1371/journal.pone.0000834"},
                {
                  "id":"10.1371/journal.pone.0244339"},
                {
                  "id":"10.1371/journal.pone.0172780"},
                {
                  "id":"10.1371/journal.pone.0205867"},
                {
                  "id":"10.1371/journal.pgen.1003428"},
                {
                  "id":"10.1371/journal.pone.0014362"},
                {
                  "id":"10.1371/journal.pone.0072864"},
                {
                  "id":"10.1371/journal.pgen.1005148"},
                {
                  "id":"10.1371/journal.pone.0276704"},
                {
                  "id":"10.1371/journal.pgen.1006164"},
                {
                  "id":"10.1371/journal.pgen.0020180"},
                {
                  "id":"10.1371/journal.pone.0136504"},
                {
                  "id":"10.1371/journal.pgen.1004560"},
                {
                  "id":"10.1371/journal.ppat.1006034"},
                {
                  "id":"10.1371/journal.pone.0009285"},
                {
                  "id":"10.1371/journal.pgen.1004096"},
                {
                  "id":"10.1371/journal.pbio.0060119"},
                {
                  "id":"10.1371/journal.pone.0006838"},
                {
                  "id":"10.1371/journal.pbio.3000094"}]
            }}';
    }
    
}
