<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurementplan\Item;
use common\models\procurementplan\ItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\procurementplan\Itemprice;
use yii\helpers\Json;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
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
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
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
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();
        $itemprice = new Itemprice();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $itemprice->item_id = $model->item_id;
            $itemprice->price_catalogue = $_POST['Item']['price_catalogue'];
            $itemprice->save(false);
            return $this->redirect(['view', 'id' => $model->item_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $itemprice = new Itemprice();
        if($model->load(Yii::$app->request->post())){
            $itemprice->item_id = $model->item_id;
            $itemprice->price_catalogue = $_POST['Item']['price_catalogue'];
            if ($model->save() && $itemprice->save()) {
                return $this->redirect(['view', 'id' => $model->item_id]);
            }
        }
         else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } catch (yii\db\IntegrityException $e) {
            Yii::$app->session->setFlash('danger', "Database integrity exception, Process not allowed...");
            return $this->redirect(['index']);
        }
    
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionUpdateprice(){
        
        if(Yii::$app->request->post('hasEditable')){
            $itemid = Yii::$app->request->post('editableKey');
            $index = Yii::$app->request->post('editableIndex');
            $attr = Yii::$app->request->post('editableAttribute');
            $model = Item::findOne($itemid);
            $model->price_catalogue = $_POST['Item'][$index][$attr];
            //$out = Json::encode(['message'=> $index]);
            if($model->save()){
                //echo $out;
                return true;
            }
        }
        
    }
}
