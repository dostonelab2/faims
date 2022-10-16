<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurementplan\Item;
use common\models\procurementplan\ItemSearch;
use common\models\procurementplan\Itemcategory;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\PpmpitemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\procurement\Division;
use common\models\procurement\Unit;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * PpmpitemController implements the CRUD actions for Ppmpitem model.
 */
class PpmpitemController extends Controller
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
     * Lists all Ppmpitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PpmpitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ppmpitem model.
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
     * Creates a new Ppmpitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ppmpitem();
        
        $categories = Itemcategory::find()->orderBy('category_name')->asArray()->all();
        $listCategories = ArrayHelper::map($categories, 'item_category_id', 'category_name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string) $model->ppmp_item_id]);
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'listCategories' => $listCategories,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'listCategories' => $listCategories,
            ]);
        }
    }
    
    public function actionAdditems()
    {
        $id = $_GET['id'];
        $year = $_GET['year'];
        if (Yii::$app->request->isAjax) {
            $ppmp = Ppmp::findOne($id); 
            if(!$ppmp->isMember())
            {
                return $this->renderAjax('_info', ['message'=>'You are not Authorized to do this action.']);   
            }elseif($ppmp->status_id == Ppmp::STATUS_SUBMITTED){
                return $this->renderAjax('_info', ['message'=>'This PPMP has been submitted for Approval.']);   
            }
        }
        
        $searchModel = new ItemSearch();
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

    public function actionUpdateitems()
    {
        $id = $_GET['id'];
        $year = $_GET['year'];
        
        $queryPpmpItems = Ppmpitem::find()->where([
                                    'ppmp_id' => $id, 
                                    'active' => 1]);
        
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
        
        //$searchModel = new ItemSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_updateitems', [
                        'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            ]);
        } else {
            return $this->render('_updateitems', [
                        'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            ]);
        }
    }
    /**
     * Updates an existing Ppmpitem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ppmp_item_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ppmpitem model.
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
     * Finds the Ppmpitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ppmpitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ppmpitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUpdateitemqty() {
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
}
