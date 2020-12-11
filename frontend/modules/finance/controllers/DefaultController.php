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
        $forVerification = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_SUBMITTED])->count();
        $forValidation = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_VERIFIED])->count();
        
        $forAllotment = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_VALIDATED])->count();
        $forObligation = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE])->count();
        $forCharging = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_ALLOTTED])->count();
        $forDisbursement = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_CERTIFIED_FUNDS_AVAILABLE])->count();
        $forApproval = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_CHARGED])->count();
        $forPayment = Request::find()->where('status_id =:status_id',[':status_id'=>Request::STATUS_APPROVED_FOR_DISBURSEMENT])->count();
        
        return $this->render('index',[
            'forVerification' => $forVerification,
            'forValidation' => $forValidation,
            'forAllotment' => $forAllotment,
            'forObligation' => $forObligation,
            'forCharging' => $forCharging,
            'forDisbursement' => $forDisbursement,
            'forApproval' => $forApproval,
            'forPayment' => $forPayment,
        ]);
    }
}
