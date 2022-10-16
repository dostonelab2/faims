<?php
namespace frontend\modules\procurement\controllers;

use common\models\procurement\Lineitembudgetobjectdetailsrealignment;
use common\models\procurement\Lineitembudgetobjectrealignment;
use common\models\procurement\Program;
use common\models\procurement\Project;
use Yii;
use common\models\procurement\Lineitembudget;
use common\models\procurement\LineitembudgetSearch;
use common\models\procurement\Type;
use common\models\procurement\Subtype;
use common\models\procurement\Division;
use common\models\procurement\Section;
use common\models\procurement\Expenditureobject;
use common\models\procurement\Lineitembudgetobject;
use common\models\procurement\LineitembudgetobjectSearch;
use common\models\procurement\Lineitembudgetobjectdetails;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

use kartik\grid\EditableColumnAction;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * LineitembudgetController implements the CRUD actions for Lineitembudget model.
 */
class LineitembudgetController extends Controller
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

   public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           /*'editLibObject' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => Lineitembudget::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
                    $fmt = Yii::$app->formatter;
                    $value = $model->$attribute;                 // your attribute value
                    if ($attribute === 'title') {           // selective validation by attribute
                        return $fmt->asText($value);       // return formatted value if desired
                    }
                    return '';                                   // empty is same as $value
               },
               'outputMessage' => function($model, $attribute, $key, $index) {
                     return '';                                  // any custom error after model save
               },
           ]
           ,*/

           'editLibObjects' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => Lineitembudgetobject::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
                   $fmt = Yii::$app->formatter;
                   $value = $model->$attribute;                 // your attribute value
                   $model->save();
                   if ($attribute === 'amount') {           // selective validation by attribute
                       return $fmt->asDecimal($value,2);       // return formatted value if desired
                   }
                   return '';                                   // empty is same as $value
               },
               'outputMessage' => function($model, $attribute, $key, $index) {
                   return '';                                  // any custom error after model save
               },
           ]
       ]);

   }

   public  function actionEditamount() {
       if (Yii::$app -> request -> post('hasEditable')) {
           $ids = Yii::$app -> request -> post('editableKey');
           $amt = Yii::$app -> request -> post('amount');
           $model = Lineitembudgetobjectrealignment::findOne($ids);
               $model->amount = floatval($amt); //$fmt->asDecimal($amt,2);
               $model->save(false);
               $out = Json::encode(['output' => $amt, 'message' => '']);
           return $out;
       }
   }
    
    /**
     * Lists all Lineitembudget models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LineitembudgetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lineitembudget model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => Lineitembudgetobject::find()
                            ->where(['line_item_budget_id' => $model->line_item_budget_id]),
                            //->orderBy('expenditure_object_id'),
            'pagination' => false,
        ]);
        
        $searchModel = new LineitembudgetobjectSearch();

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Creates a new Lineitembudget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lineitembudget();
        $session = Yii::$app->session;
        
        $types = Type::find()->all();
        $subtypes = Subtype::find()->all();
        $divisions = Division::find()->all();
        $sections = Section::find()->all();

        $listTypes = ArrayHelper::map($types, 'type_id', 'name');
        $listSubTypes = ArrayHelper::map($subtypes, 'subtype_id', 'name');
        $listDivisions = ArrayHelper::map($divisions,'division_id','name');
        $listSections = ArrayHelper::map($sections,'section_id','name');
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            //$session->set('savepopup',"executed");
                //return $this->redirect('index');
                return $this->redirect(['view', 'id' => $model->line_item_budget_id]);
        }else{
            return $this->renderAjax('create', [
                'model' => $model,
                'listTypes'=>$listTypes,
                'listSubTypes' => $listSubTypes,
                'listDivisions'=>$listDivisions,
                'listSections'=>$listSections,
            ]) ;
        }
    }

    /**
     * Updates an existing Lineitembudget model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $session = Yii::$app->session;
        $types = Type::find()->all();
        $subtypes = Subtype::find()->all();
        $divisions = Division::find()->all();
        $sections = Section::find()->all();
        $project = Project::find()->all();
        $program = Program::find()->all();
        $listTypes = ArrayHelper::map($types, 'type_id', 'name');
        $listSubTypes = ArrayHelper::map($subtypes, 'subtype_id', 'name');
        $listDivisions = ArrayHelper::map($divisions,'division_id','name');
        $listSections = ArrayHelper::map($sections,'section_id','name');
        $listProject = ArrayHelper::map($project,'project_id','name');
        $listProgram = ArrayHelper::map($program,'program_id','name');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->line_item_budget_id]);
        } else {
            return $this->render('update', [
                'listTypes'=>$listTypes,
                'listSubTypes' => $listSubTypes,
                'listDivisions'=>$listDivisions,
                'listSections'=>$listSections,
                'listProject'=>$listProject,
                'listProgram'=>$listProgram,
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lineitembudget model.
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
     *
     */

    public function actionDeletealignment($id,$mainid)
    {
        $this->findAlignment($id)->delete();

        $model = Lineitembudgetobject::findOne($mainid);
        $dataProvider2 = $this->getAlignment($mainid);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_realign_objectajax', [
                'model' => $model,
                'dataProvider2' => $dataProvider2,
                'id' => $mainid,
            ]);
           // \yii\web\Response::redirect(['return'] , 302 , false);
        } else {
            return '';
        }
        //return $this->redirect(['index']);
        //\yii\web\Response::redirect(['return'] , 302 , false);
    }

    /**
     * Finds the Lineitembudget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lineitembudget the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lineitembudget::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findAlignment($id)
    {
        if (($model = Lineitembudgetobjectrealignment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    public function actionUpdateobjects($id) 
    {
        $model = Lineitembudgetobject::findOne($id);
        $query = Expenditureobject::find()
            ->orderBy(['expenditure_sub_class_id' => SORT_ASC]);         
        
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_libobjects', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
            ]);
        } else {
            return $this->render('_libobjects', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                        'id' => $id,
            ]);
        }
    }

    public function actionUpdaterealignment($id)
    {
       $model = Lineitembudgetobject::findOne($id);
       $dataProvider = $this->getLIB($id);
       $dataProvider2 = $this->getAlignment($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_reallign_libobjects', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'dataProvider2' => $dataProvider2,
                'id' => $id,
            ]);
        }
        else {
            return $this->render('_reallign_libobjects', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'dataProvider2' => $dataProvider2,
                'id' => $id,
            ]);
        }
    }


    function getLIB($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_line_item_budget_object`.`line_item_budget_object_id`,`tbl_line_item_budget_object`.`line_item_budget_id`,`tbl_expenditure_object`.`name`,`tbl_expenditure_object`.`object_code` 
FROM `tbl_expenditure_object`
INNER JOIN `tbl_line_item_budget_object` 
ON `tbl_line_item_budget_object`.`expenditure_object_id` = `tbl_expenditure_object`.`expenditure_object_id`
INNER JOIN `tbl_line_item_budget` 
ON `tbl_line_item_budget`.`line_item_budget_id` = `tbl_line_item_budget_object`.`line_item_budget_id`
WHERE `tbl_line_item_budget_object`.`line_item_budget_id` = ".$id;
        $pordetails = $con->createCommand($sql)->queryAll();
        $x = 0;
        foreach ($pordetails as $pr) {
            $x++;
            $data[] = ['line_item_budget_object_id' => $pr["line_item_budget_object_id"],
                'line_item_budget_id' => $pr["line_item_budget_id"],
                'name' => $pr["name"],
                'object_code' => $pr["object_code"],
            ];
        }
        if ($x == 0) {
            $data[] = ['line_item_budget_object_id' => '',
                'line_item_budget_id' => '',
                'name' => '',
                'object_code' => '',
            ];
        }

        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['line_item_budget_object_id',
                    'line_item_budget_id',
                    'name',
                    'object_code',
                ],
            ],
        ]);
        $pordetails = $provider;
        return $pordetails;
    }

    function getAlignment($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_line_item_budget_object_realignment`.`line_item_budget_object_realignment_id`,
                `tbl_line_item_budget_object_realignment`.`line_item_budget_object_id`,
                `tbl_line_item_budget_object_realignment`.`line_item_budget_id`,`tbl_expenditure_object`.`name`,
                `tbl_expenditure_object`.`object_code`,
                `tbl_line_item_budget_object_realignment`.`amount` 
                FROM `tbl_expenditure_object`
                INNER JOIN `tbl_line_item_budget_object_realignment` 
                ON `tbl_line_item_budget_object_realignment`.`expenditure_object_id` = `tbl_expenditure_object`.`expenditure_object_id`
                INNER JOIN `tbl_line_item_budget` 
                ON `tbl_line_item_budget`.`line_item_budget_id` = `tbl_line_item_budget_object_realignment`.`line_item_budget_id`
                WHERE `tbl_line_item_budget_object_realignment`.`line_item_budget_id`=".$id;
        $pordetails = $con->createCommand($sql)->queryAll();
        $x = 0;
        foreach ($pordetails as $pr) {
            $x++;
            $data[] = ['line_item_budget_object_realignment_id' => $pr["line_item_budget_object_realignment_id"],
                'line_item_budget_object_id' => $pr["line_item_budget_object_id"],
                'line_item_budget_id' => $pr["line_item_budget_id"],
                'name' => $pr["name"],
                'object_code' => $pr["object_code"],
                'amount' => $pr["amount"],
            ];
        }
        if ($x == 0) {
            $data[] = ['line_item_budget_object_realignment_id' => '',
                'line_item_budget_object_id' => '',
                'line_item_budget_id' => '',
                'name' => '',
                'object_code' => '',
                'amount' => '',
            ];
        }

        $provider = new ArrayDataProvider([
            'key'=> 'line_item_budget_object_realignment_id',
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['line_item_budget_object_realignment_id',
                    'line_item_budget_object_id',
                    'line_item_budget_id',
                    'name',
                    'object_code',
                    'amount',
                ],
            ],
        ]);
        $pordetails = $provider;
        return $pordetails;
    }

    public function actionForwardlib() {
    $request = Yii::$app->request;
    $array_rows = $request->post('array_rows');
    $data_table = $request->post('tabledata');
    $arr2 = json_decode($array_rows, true);
    $arr = json_decode($data_table, true);
    $mstat="";
    $s = 0;
    $data = $arr2;
    $sizess = count($data) - 1;
    $connection = Yii::$app->db;
    $procCon = Yii::$app->procurementdb;
    $transaction = $procCon->beginTransaction();
    $mdata="";
    if ($sizess=='-1') {
        // Do nothing...
    }else{

        try {
            do {
                $mdata = $data[$s];
                $LibObject = Lineitembudgetobject::findOne($mdata);
                $LibObjectDetails = Lineitembudgetobjectdetails::findOne($mdata);
                if ($LibObject !== null) {
                    $procCon->createCommand()->insert('`tbl_line_item_budget_object_realignment`',
                        ['line_item_budget_object_id' => $mdata,
                            'line_item_budget_id' => $LibObject->line_item_budget_id,
                            'expenditure_object_id'=>$LibObject->expenditure_object_id,
                            'amount'=>'0.00'
                        ])->execute();
                } else {
                    //throw new NotFoundHttpException('The requested page does not exist.');
                }
                
                if ($LibObjectDetails !== null) {
                    $pID = Lineitembudgetobjectrealignment::find()->where(['line_item_budget_object_id'=> $LibObjectDetails->line_item_budget_object_id])->one();
                    $procCon->createCommand()->insert('`tbl_line_item_budget_object_details_realignment`',
                        ['line_item_budget_object_realignment_id' => $pID->line_item_budget_object_realignment_id ,
                            'line_item_budget_object_id'=> $LibObjectDetails->line_item_budget_object_id,
                            'object_detail_id' => $LibObjectDetails->object_detail_id,
                            'quantity'=> $LibObjectDetails->quantity,
                            'name'=> $LibObjectDetails->name,
                            'details'=> $LibObjectDetails->details,
                            'amount'=> $LibObjectDetails->amount
                        ])->execute();
                } else {
                    //throw new NotFoundHttpException('The requested page does not exist.');
                }
                $s++;
            } while ($s <= $sizess);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    return $sizess;
}


    public function actionBackwardlib() {
        $request = Yii::$app->request;
        $array_rows = $request->post('array_rows');
        $data_table = $request->post('tabledata');
        $arr2 = json_decode($array_rows, true);
        $arr = json_decode($data_table, true);
        $mstat="";
        $s = 0;
        $data = $arr2;
        $sizess = count($data) - 1;
        $connection = Yii::$app->db;
        $procCon = Yii::$app->procurementdb;
        $transaction = $procCon->beginTransaction();
        $mdata="";
        if ($sizess=='-1') {
            // Do nothing...
        }else{
            try {
                do {
                    $mdata = $data[$s];
                    $LibObject = Lineitembudgetobjectrealignment::findOne($mdata);
                    $LibObjectDetails = Lineitembudgetobjectdetailsrealignment::findOne($mdata);
                    if ($LibObject !== null) {

                    } else {
                        //throw new NotFoundHttpException('The requested page does not exist.');
                    }
                    if ($LibObjectDetails !== null) {

                    } else {
                        //throw new NotFoundHttpException('The requested page does not exist.');
                    }
                    $s++;
                } while ($s <= $sizess);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return $sizess;
    }

    
    public function actionAddexpenditure()
    {
        /** Post Data
            checked	                true
            expenditureObjectId	    16
            lineItemBudgetId        9
        **/
        $expenditureObject = Expenditureobject::findOne($_POST['expenditureObjectId']);
        $lineitembudgetObject = Lineitembudgetobject::find()->where([
                                    'line_item_budget_id' => $_POST['lineItemBudgetId'], 
                                    'expenditure_object_id' => $_POST['expenditureObjectId']])->one();
        
        if($lineitembudgetObject)
        {
            //echo Json::encode(['message'=>$_POST['checked']]);
            if($_POST['checked'] == 'true'){
                $lineitembudgetObject->active = 1;
                $lineitembudgetObject->save(false);
            }
            else{
                $lineitembudgetObject->active = 0;
                $lineitembudgetObject->save(false);
            }
        }else{
            $libObject = new Lineitembudgetobject();
            $libObject->line_item_budget_id = $_POST['lineItemBudgetId'];
            $libObject->expenditure_object_id = $_POST['expenditureObjectId'];
            $libObject->amount = 0.00;
            $libObject->save();
        }
        
        echo Json::encode(['message'=>print_r($lineitembudgetObject)]);
    }
    
    public function actionAddobjectdetails($id) 
    {
        $model = new Lineitembudgetobjectdetails();
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('/lineitembudgetobjectdetails/_form', [
                        'model' => $model,
                        'id' => $id,
            ]);
        } else {
            return $this->render('/lineitembudgetobjectdetails/_form', [
                        'model' => $model,
                        'id' => $id,
            ]);
        }
    }
}
