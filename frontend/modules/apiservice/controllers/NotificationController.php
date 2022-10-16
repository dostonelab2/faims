<?php

namespace frontend\modules\apiservice\controllers;

use Yii;
use common\models\apiservice\Notification;
use common\models\apiservice\NotificationSearch;

use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use linslin\yii2\curl;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends Controller
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
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $data = Json::encode(['recipient'=>'639177975944','title'=>'Test 22','message'=>'Test Message 22'],JSON_NUMERIC_CHECK);

        //$hash, $sender, $recipient, $title, $message, $via, $module, $action
        $response = Yii::$app->Notification->sendSMS('poipoipoipoipoi', 2, '639177975944', 'Component Test', 'This is an SMS via component in Yii2', 'FAIMS', $this->module->id, $this->action->id);
        
        /*$url='https://api.dost9.ph/sms/messages';
                   
        $curl = new curl\Curl();
        
        //$notification = $curl->get($url);
        
        $response = $curl->setPostParams([
            'hash' => 'sdfghjklkjhgf', 
            'sender' => 1, 
            'recipient' => '639177975944', 
            'status' => NULL, 
            'title' => 'complete post', 
            'message' => 'testing for complete posting', 
            'via' => 'FAIMS', 
            'module' => $this->module->id, 
            'action' => $this->action->id
            
            //'recipient' => '639177975944',
            //'title' => 'Request for Verification',
            //'message' => 'verification message'
         ])
         ->post($url);
        */
        /*$notification = $curl->setRequestBody($data)
        ->setHeaders([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ])->post($url);*/
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'response' => $response
        ]);
        
    }
    
    public function createSMS($sender, $recipient, $status = NULL, $title = NULL, $message, $via = NULL, $module = NULL, $action = NULL)
    {
        try{
            
            $folder     = 'D:/sms/outgoing/';
            //$folder     = '/var/spool/sms/outgoing/';
            $filename   = tempnam("","");

            $myfile = fopen($filename, "wb") or die("Unable to open file!");
            $sms_recipient = 'To: '.$recipient.PHP_EOL.PHP_EOL;

            fwrite($myfile, $sms_recipient);
            fwrite($myfile, $message);
            fclose($myfile);
            
            copy($filename, $folder.basename($filename));
            unlink($filename);
            
        } catch (Exception $e) {

        }
        
        /*try{
            $folder     = '/var/spool/sms/outgoing/';
            if (!file_exists($folder)) return;
            $filename   = tempnam("","");   
            $file       = fopen($filename,"w");
            fwrite($file, "To: 639177975944\n");
            //fwrite($file, "Type: ".$sms_type."\n");
            //fwrite($file, "bId: ".$bulletin_id."\n");
            //fwrite($file, "rId: ".$recipient_id."\n\n");
            fwrite($file, "New Message\n");
            fclose($file);
            copy($filename, $folder.basename($filename));
            unlink($filename);
        } catch (Exception $e) {

        }*/
        
    }

    /**
     * Displays a single Notification model.
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
     * Creates a new Notification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notification();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->notification_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Notification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->notification_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Notification model.
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
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
