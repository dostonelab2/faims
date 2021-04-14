<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Purchaserequest;
use common\models\procurement\Tmpitem;
use frontend\models\PurchaserequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

/**
 * Purchaserequest2Controller implements the CRUD actions for Purchaserequest model.
 */
class Purchaserequest2Controller extends Controller
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
     * Lists all Purchaserequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaserequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchaserequest model.
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
     * Creates a new Purchaserequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        //echo('<pre>');
        //var_dump(Json::encode($items));
        //echo('</pre>');
        $model = new Purchaserequest();
        
        $items = Tmpitem::find()->where(['session_id' => Yii::$app->session->getId()]);
        $itemDataProvider = new ActiveDataProvider([
            'query' => $items,
            'pagination' => false,
            'sort' => [
                'attributes' => ['item_id'],
            ],
        ]);
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['checkItem'])) {
                $tmpitem = Tmpitem::find()->where(['tmppritems_id' => $_POST['itemId']])->one();
                if ($tmpitem) {
                    //echo Json::encode(['message'=>$ppmp_item]);
                    if ($_POST['checked'] == 'true') {
                        $items->where(['section_id' => $_POST['section'], 'checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                        $tmpitem->checked = 1;
                        $tmpitem->save(false);
                        return $this->renderAjax('create', [
                            'model' => $model,
                            'itemDataProvider' => $itemDataProvider,
                        ]);
                    } else {
                        $items->where(['section_id' => $_POST['section'], 'checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                        $tmpitem->checked = 0;
                        $tmpitem->save(false);
                        return $this->renderAjax('create', [
                            'model' => $model,
                            'itemDataProvider' => $itemDataProvider
                        ]);
                    }
                }
            } elseif (isset($_POST['selectSection']) == true) {
                $this->deleteTempItem();
                $this->setTempItem('section_id=' . '\'' . $_POST['section'] . '\' AND project_id=\'\'');
                $items->where(['section_id' => $_POST['section'], 'session_id' => Yii::$app->session->getId()]);
                //var_dump($items);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['selectProject']) == true) {
                $this->deleteTempItem();
                $this->setTempItem('project_id=' . '\'' . $_POST['project'] . '\' AND section_id=\'\'');
                $items->where(['project_id' => $_POST['project'], 'session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
                var_dump($items);
            } elseif (isset($_POST['reloadsectionitems']) == true) {
                //$this->setTempItem();
                $items->where(['section_id' => $_POST['section'], 'checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['reloadprojectitems']) == true) {
                //$this->setTempItem();
                $items->where(['project_id' => $_POST['project'], 'checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['removeitem']) == true) {
                //$this->setTempItem();
                $tmpitem = Tmpitem::find()->where(['tmppritems_id' => $_POST['tmppritems_id']])->one();
                $items->where(['section_id' => $_POST['section'], 'checked' => 1, 'session_id' => Yii::$app->session->getId()]);
                $tmpitem->checked = 0;
                $tmpitem->save(false);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } elseif (isset($_POST['reloadremoveditems']) == true) {
                //$this->setTempItem();
                $items->where(['section_id' => $_POST['section'], 'session_id' => Yii::$app->session->getId()]);
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            } else {
                //$con2 = Yii::$app->procurementdb;
                //$sql2 = 'CREATE TEMPORARY TABLE ' . Yii::$app->session->getId() . '
                //        (
                //            SELECT * FROM vw_ppmp_items
                //        )';
                //$con2->createCommand($sql2)->execute();
                //$con = Yii::$app->procurementdb;
                //$sql = 'SELECT item_id, description, unit,unit_description, cost,SUM(approved_qty) AS qty FROM '.Yii::$app->session->getId().' WHERE YEAR = 2021 AND division_id = 2 AND status_id = 3 GROUP BY item_id';
                //$items = $con->createCommand($sql)->queryAll();
                $items->where(['section_id' => '','project_id' => '']);
                $this->deleteTempItem();
                return $this->renderAjax('create', [
                    'model' => $model,
                    'itemDataProvider' => $itemDataProvider
                ]);
            }
        }/*else{
            return $this->redirect('index');
        }*/
    }

    /**
     * Updates an existing Purchaserequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->purchase_request_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Purchaserequest model.
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
     * Finds the Purchaserequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchaserequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchaserequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function setTempItem($selection)
    {
        $session_id = '\'' . Yii::$app->session->getId() . '\'';
        //if (Yii::$app->request->isAjax) {
            $con = Yii::$app->procurementdb;
            $sql =
                'INSERT INTO `tmppritems`(`session_id` ,`ppmp_id`, `division_id`, 
                    `section_id`, `project_id`,
                    `year`, `item_id`,
                    `description`, `unit`,
                    `unit_description`, `cost`,
                    `checked`, `qty`,
                    `approved_qty`, `supplemental`, 
                    `status_id`,
                    `date`) 
                SELECT ' . $session_id . ' AS session_id,
                    `ppmp_id`, `division_id`, 
                    `section_id`, `project_id`,
                    `year`, `item_id`,
                    `description`, `unit`,
                    `unit_description`, `cost`,
                    `checked`, `qty`,
                    `approved_qty`, `supplemental`, 
                    `status_id`,
                    DATE(NOW()) as date
                FROM vw_ppmp_items WHERE '.$selection.
                    ' AND year=' . $_POST['year'] . ' AND status_id=3
                    AND NOT EXISTS (SELECT session_id FROM tmppritems
                                    WHERE session_id = ' . $session_id . ')';
            $con->createCommand($sql)->execute();
        //}
    }
    public function deleteTempItem()
    {
        $con = Yii::$app->procurementdb;
        $sql = 'DELETE FROM tmppritems WHERE session_id=' . '\'' . Yii::$app->session->getId() . '\' OR date < DATE(NOW())';
        $con->createCommand($sql)->execute();
    }
    public function actionUpdateqty()
    {
        if (Yii::$app->request->isAjax) {
            $tmpitem = Tmpitem::find()->where(['tmppritems_id' => $_POST['item_id']])->one();
            $tmpitem->qty = $_POST['qty'];
            $tmpitem->save(false);
        }
    }
}
