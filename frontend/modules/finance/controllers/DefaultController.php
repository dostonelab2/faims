<?php
namespace frontend\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\finance\Request;
use common\models\sec\Blockchain;

class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        /*** RUN ONCE START: FOR MARKING PREVIOUSLY COMPLETED REQUESTS
        $approved = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>70])->all();
        
        foreach($approved as $request){
            $request->status_id = 80;
            if($request->save(false)){
                $request->osdv->status_id = 80;
                if($request->osdv->save(false)){
                    $index = $request->osdv->osdv_id;
                    $scope = 'Osdv';
                    $data = $request->osdv->osdv_id.':'.$request->request_id.':'.$request->osdv->type_id.':'.$request->osdv->expenditure_class_id.':'.$request->osdv->osdv_attributes.':'.$request->osdv->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                }
            }
        }
        RUN ONCE END***/
        
        $forVerification = Request::find()->where('status_id =:status_id AND cancelled =0',[':status_id'=>Request::STATUS_SUBMITTED])->count();
        $forValidationFASS = Request::find()->where('status_id =:status_id AND cancelled =0 AND (division_id = 2 OR division_id = 4)',[':status_id'=>Request::STATUS_VERIFIED])->count();
        $forValidationFASSdata = Request::find()->where('status_id =:status_id AND cancelled =0 AND (division_id = 2 OR division_id = 4)',[':status_id'=>Request::STATUS_VERIFIED]);
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
            'forValidationFASSdata' => $forValidationFASSdata,
            'forValidationFOS' => $forValidationFOS,
            'forAllotment' => $forAllotment,
            'forObligation' => $forObligation,
            'forCharging' => $forCharging,
            'forChargingData' => $forChargingData,
            'forDisbursement' => $forDisbursement,
            'forApproval' => $forApproval,
            'forPayment' => $forPayment,
            //'approved' => $approved,
        ]);
    }
    
    public function actionIndex2()
    {
        return $this->render('index3');
    }
}
