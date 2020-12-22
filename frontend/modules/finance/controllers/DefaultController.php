<?php
namespace frontend\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\finance\Request;

class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $forVerification = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_SUBMITTED])->count();
        $forValidationFASS = Request::find()->where('status_id =:status_id AND cancelled =0 AND (division_id = 2 OR division_id = 4)',[':status_id'=>Request::STATUS_VERIFIED])->count();
        $forValidationFOS = Request::find()->where('status_id =:status_id AND cancelled =0 AND (division_id = 3)',[':status_id'=>Request::STATUS_VERIFIED])->count();
        $forAllotment = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_VALIDATED])->count();
        $forObligation = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE])->count();
        $forCharging = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_ALLOTTED])->count();
        $forChargingData = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_ALLOTTED]);
        $forDisbursement = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_CERTIFIED_FUNDS_AVAILABLE])->count();
        $forApproval = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_CHARGED])->count();
        $forPayment = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_APPROVED_FOR_DISBURSEMENT])->count();
        
        return $this->render('index',[
            'forVerification' => $forVerification,
            'forValidationFASS' => $forValidationFASS,
            'forValidationFOS' => $forValidationFOS,
            'forAllotment' => $forAllotment,
            'forObligation' => $forObligation,
            'forCharging' => $forCharging,
            'forChargingData' => $forChargingData,
            'forDisbursement' => $forDisbursement,
            'forApproval' => $forApproval,
            'forPayment' => $forPayment,
        ]);
    }
}
