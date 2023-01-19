<?php
namespace frontend\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\finance\Request;
use common\models\finance\Requeststat;
use common\models\sec\Blockchain;
use common\models\system\User;

use kartik\helpers\Html;
use yii\helpers\Url;

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
        
        
        $recentActions = Blockchain::find()->orderBy(['blockchain_id' => SORT_DESC])->limit(6)->all();
        
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
            'recentActions' => $recentActions,
        ]);
    }
    
    public function actionIndex2()
    {
        return $this->render('dashboard');
    }

    public function actionVerificationdash()
    {
        if(isset($_GET['year']))
            $year = $_GET['year'];
        else
            $year = date('Y');

        $year_array = [2022, 2021, 2020];

        $toolbars = '';
        for($i=0; $i<3; $i++){
            $toolbars .= Html::a($year_array[$i], ['verificationdash?year='.$year_array[$i]], [
                'class' => 'btn btn-outline-secondary',
                'style' => 'display: inline-block; width:60px; height:40px; font-size: 120%;',
                'data-pjax' => 0, 
            ]);
        }

        $within_3_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VERIFIED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['<=', 'number_of_days', 3])
            ->count();

        $within_7_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VERIFIED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 3])
            ->andWhere(['<=', 'number_of_days', 7])
            ->count();

        $within_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VERIFIED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 7])
            ->andWhere(['<=', 'number_of_days', 20])
            ->count();

        $more_than_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VERIFIED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 20])
            ->count();
       
        return $this->render('verificationdash', [
            'within_3_days'=>$within_3_days,
            'within_7_days'=>$within_7_days,
            'within_20_days'=>$within_20_days,
            'more_than_20_days'=>$more_than_20_days,
            'toolbars'=>$toolbars,
        ]);
    }

    public function actionValidationdash()
    {
        if(isset($_GET['year']))
            $year = $_GET['year'];
        else
            $year = date('Y');

        $year_array = [2022, 2021, 2020];

        $toolbars = '';
        for($i=0; $i<3; $i++){
            $toolbars .= Html::a($year_array[$i], ['validationdash?year='.$year_array[$i]], [
                'class' => 'btn btn-outline-secondary',
                'style' => 'display: inline-block; width:60px; height:40px; font-size: 120%;',
                'data-pjax' => 0, 
            ]);
        }
        $within_3_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VALIDATED])
            ->orWhere(['status_id' => Request::STATUS_FOR_DISBURSEMENT])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['<=', 'number_of_days', 3])
            ->count();

        $within_7_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VALIDATED])
            ->orWhere(['status_id' => Request::STATUS_FOR_DISBURSEMENT])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 3])
            ->andWhere(['<=', 'number_of_days', 7])
            ->count();

        $within_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VALIDATED])
            ->orWhere(['status_id' => Request::STATUS_FOR_DISBURSEMENT])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 7])
            ->andWhere(['<=', 'number_of_days', 20])
            ->count();

        $more_than_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_VALIDATED])
            ->orWhere(['status_id' => Request::STATUS_FOR_DISBURSEMENT])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 20])
            ->count();
       
        return $this->render('validationdash', [
            'within_3_days'=>$within_3_days,
            'within_7_days'=>$within_7_days,
            'within_20_days'=>$within_20_days,
            'more_than_20_days'=>$more_than_20_days,
            'toolbars'=>$toolbars,
        ]);
    }

    public function actionObligationdash()
    {
        if(isset($_GET['year']))
            $year = $_GET['year'];
        else
            $year = date('Y');

        $year_array = [2022, 2021, 2020];

        $toolbars = '';
        for($i=0; $i<3; $i++){
            $toolbars .= Html::a($year_array[$i], ['obligationdash?year='.$year_array[$i]], [
                'class' => 'btn btn-outline-secondary',
                'style' => 'display: inline-block; width:60px; height:40px; font-size: 120%;',
                'data-pjax' => 0, 
            ]);
        }
        $within_3_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_ALLOTTED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['<=', 'number_of_days', 3])
            ->count();

        $within_7_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_ALLOTTED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 3])
            ->andWhere(['<=', 'number_of_days', 7])
            ->count();

        $within_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_ALLOTTED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 7])
            ->andWhere(['<=', 'number_of_days', 20])
            ->count();

        $more_than_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_ALLOTTED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 20])
            ->count();
       
        return $this->render('obligationdash', [
            'within_3_days'=>$within_3_days,
            'within_7_days'=>$within_7_days,
            'within_20_days'=>$within_20_days,
            'more_than_20_days'=>$more_than_20_days,
            'toolbars'=>$toolbars,
        ]);
    }

    public function actionDisbursementdash()
    {
        if(isset($_GET['year']))
            $year = $_GET['year'];
        else
            $year = date('Y');

        $year_array = [2022, 2021, 2020];

        $toolbars = '';
        for($i=0; $i<3; $i++){
            $toolbars .= Html::a($year_array[$i], ['disbursementdash?year='.$year_array[$i]], [
                'class' => 'btn btn-outline-secondary',
                'style' => 'display: inline-block; width:60px; height:40px; font-size: 120%;',
                'data-pjax' => 0, 
            ]);
        }
        $within_3_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_CHARGED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['<=', 'number_of_days', 3])
            ->count();

        $within_7_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_CHARGED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 3])
            ->andWhere(['<=', 'number_of_days', 7])
            ->count();

        $within_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_CHARGED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 7])
            ->andWhere(['<=', 'number_of_days', 20])
            ->count();

        $more_than_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_CHARGED])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 20])
            ->count();
       
        return $this->render('disbursementdash', [
            'within_3_days'=>$within_3_days,
            'within_7_days'=>$within_7_days,
            'within_20_days'=>$within_20_days,
            'more_than_20_days'=>$more_than_20_days,
            'toolbars'=>$toolbars,
        ]);
    }
    
    public function actionApprovaldash()
    {
        if(isset($_GET['year']))
            $year = $_GET['year'];
        else
            $year = date('Y');

        $year_array = [2022, 2021, 2020];

        $toolbars = '';
        for($i=0; $i<3; $i++){
            $toolbars .= Html::a($year_array[$i], ['approvaldash?year='.$year_array[$i]], [
                'class' => 'btn btn-outline-secondary',
                'style' => 'display: inline-block; width:60px; height:40px; font-size: 120%;',
                'data-pjax' => 0, 
            ]);
        }

        $within_3_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_APPROVED_FOR_DISBURSEMENT])
            // ->orWhere(['status_id' => Request::STATUS_APPROVED_PARTIAL])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['<=', 'number_of_days', 3])
            ->count();

        $within_7_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_APPROVED_FOR_DISBURSEMENT])
            // ->orWhere(['status_id' => Request::STATUS_APPROVED_PARTIAL])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 3])
            ->andWhere(['<=', 'number_of_days', 7])
            ->count();

        $within_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_APPROVED_FOR_DISBURSEMENT])
            // ->orWhere(['status_id' => Request::STATUS_APPROVED_PARTIAL])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 7])
            ->andWhere(['<=', 'number_of_days', 20])
            ->count();

        $more_than_20_days = Requeststat::find()
            ->where(['status_id' => Request::STATUS_APPROVED_FOR_DISBURSEMENT])
            // ->orWhere(['status_id' => Request::STATUS_APPROVED_PARTIAL])
            ->andWhere(['YEAR(`stat_date`)' => $year])
            ->andWhere(['>', 'number_of_days', 20])
            ->count();
       
        return $this->render('approvaldash', [
            'within_3_days'=>$within_3_days,
            'within_7_days'=>$within_7_days,
            'within_20_days'=>$within_20_days,
            'more_than_20_days'=>$more_than_20_days,
            'toolbars'=>$toolbars,
        ]);
    }
    
}
