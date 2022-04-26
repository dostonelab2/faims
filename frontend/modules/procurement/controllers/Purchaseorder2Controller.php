<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Purchaseorderdetails;
use common\models\procurement\PurchaseorderdetailsSearch;
use common\models\procurement\Purchaseorder;
use common\models\procurement\Purchaserequest;
use common\models\procurement\Bidsdetails;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Purchaseorder2Controller implements the CRUD actions for Purchaseorderdetails model.
 */
class Purchaseorder2Controller extends Controller
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
     * Lists all Purchaseorderdetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseorderdetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchaseorderdetails model.
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
     * Updates an existing Purchaseorderdetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate()
    {
        $model = new Bidsdetails();
        // $model2 = new Purchaseorder();
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $id = $session->get('myID');
        $myid = $session->get('myID2');
        $model = $this->findModelBidDetails($id);
        $model2 = $this->findModelBidDetails($myid);
        $delivery = $request->post('txtdelivery');
        $delivery_date = $request->post('txtdelivery_date');
        $delivery_term = $request->post('txtdelivery_term');
        $payment_term = $request->post('txtpayment_term');
        $mod_of_proc = $request->post('txtmode_of_procurement');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Purchaseorder::updateAll(
                [
                    'place_of_delivery' => $delivery,
                    'date_of_delivery' => $delivery_date,
                    'delivery_term' => $delivery_term,
                    'payment_term' => $payment_term,
                    'mode_of_procurement' => $mod_of_proc,
                ],
                'purchase_order_id = ' . $myid
            );

            return $this->redirect('index');
        }
    }



    /**
     * Finds the Purchaseorderdetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchaseorderdetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchaseorderdetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModelBidDetails($id)
    {
        if (($model = BidsDetails::findOne($id)) !== null) {
            return $model;
        } else {
            //return var_dump($model);
            //throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
