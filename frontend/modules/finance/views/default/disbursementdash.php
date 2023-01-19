<?php 
use yii\bootstrap\Modal;

use yii\helpers\ArrayHelper;

use common\models\procurement\Division;
use common\models\finance\Request;
use common\models\finance\Requeststat;
use common\models\finance\Requesttype;
use common\models\finance\Obligationtype;

use common\models\sec\Blockchain;

use common\models\system\User;

$this->title = 'Dashboard';
// $this->params['breadcrumbs'][] = ['label' => 'Docman', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

//echo $model->status_id.'<br/>';
//echo Os::generateOsNumber($model->request->obligation_type_id,$model->request->request_date);
?>   
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Disbursement Dashboard - Accounting
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <?= $toolbars ?>

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $within_3_days ?></h3>

              <p>WITHIN 3 DAYS</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $within_7_days ?></h3>

              <p>WITHIN 7 DAYS</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $within_20_days ?></h3>

              <p>WITHIN 20 DAYS</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $more_than_20_days ?></h3>

              <p>21 DAYS OR MORE</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->

<?php
  $requestType = Requesttype::find()->where('active =:active',[':active'=>1])->all();
  $fundSource = Obligationtype::find()->all();
  $division = Division::find()->select(['division_id'])->asArray()->all();

  $overallProcessing = Requeststat::find()
  ->select(['SUM(number_of_days) AS days', 'request_id'])
  ->where(['YEAR(`stat_date`)' => 2022])
  // ->andWhere(['status_id' => 55])
  ->groupBy('request_id')
  ->asArray()
  ->all();

  // $overallProcessing = Request::find()
  // ->where(['YEAR(`request_date`)' => 2022])
  // ->andWhere(['>=','status_id', 70])
  // ->limit(100)
  // ->all();

  echo 'Overall Processing';
  echo '<pre>';
  print_r($overallProcessing);
  echo '</pre>';

  $requests = [
    'within_3_days' => 0,
    'within_7_days' => 0,
    'within_20_days' => 0,
    'beyond_20_days' => 0
  ];

  foreach($overallProcessing as $request_stats){
    // echo $request_stats['days'].'<br/>';
    switch ($request_stats['days']) {
      case ($request_stats['days'] <= 3):
        $requests['within_3_days'] += 1;
        break;

      case ($request_stats['days'] <= 7):
        $requests['within_7_days'] += 1;
        break;

      case ($request_stats['days'] <= 20):
        $requests['within_20_days'] += 1;
        break;

      case ($request_stats['days'] > 20):
        $requests['beyond_20_days'] += 1;
        break;

      default:
    }
  }
    echo '<pre>';
    print_r($requests);
    echo '</pre>';


  $requestByDivision = Request::find()
  ->select(['COUNT(request_id) AS cnt', 'division_id'])
  ->where(['YEAR(`request_date`)' => 2022])
  ->andWhere(['cancelled' => 0])
  ->groupBy('division_id')
  ->asArray()
  ->all();

  $countsByDivision = ArrayHelper::map($requestByDivision, 'division_id', 'cnt');
  echo 'Requests by Division';
  echo '<pre>';
  //print_r($countsByDivision);
  echo '</pre>';

  $requestApproval = Request::find()
  ->select(['COUNT(request_id) AS cnt', 'status_id'])
  ->where(['YEAR(`request_date`)' => 2022])
  ->andWhere(['cancelled' => 0])
  ->groupBy('status_id')
  ->asArray()
  ->all();

  $countsByStatus = ArrayHelper::map($requestApproval, 'status_id', 'cnt');
  echo 'Request Approval';
  echo '<pre>';
  //print_r($countsByStatus);
  echo '</pre>';

  $requestCancelled = Request::find()
  ->select(['COUNT(request_id) AS cnt', 'cancelled'])
  ->where(['YEAR(`request_date`)' => 2022])
  // ->andWhere(['cancelled' => 0])
  ->groupBy('cancelled')
  ->asArray()
  ->all();

  $countsByCancelled = ArrayHelper::map($requestCancelled, 'cancelled', 'cnt');
  echo 'Request Cancellation';
  echo '<pre>';
  //print_r($countsByCancelled);
  echo '</pre>';

  $requestMonth = Request::find()
  ->select(['COUNT(request_id) AS cnt', 'MONTH(`request_date`) as month'])
  ->where(['YEAR(`request_date`)' => 2022])
  ->andWhere(['cancelled' => 0])
  ->groupBy('month')
  ->asArray()
  ->all();

  $countsByMonth = ArrayHelper::map($requestMonth, 'month', 'cnt');
  echo 'Requests by Month';
  echo '<pre>';
  //print_r($countsByMonth);
  echo '</pre>';

  $requestByFundSource = Request::find()
  ->select(['COUNT(request_id) AS cnt', 'obligation_type_id'])
  ->where(['YEAR(`request_date`)' => 2022])
  ->andWhere(['cancelled' => 0])
  ->groupBy('obligation_type_id')
  ->asArray()
  ->all();

  $countsByFundSource = ArrayHelper::map($requestByFundSource, 'obligation_type_id', 'cnt');
  echo 'Requests by Fund Source';
  echo '<pre>';
  //print_r($countsByFundSource);
  echo '</pre>';

  $requestByType = Request::find()
  ->select(['COUNT(request_id) AS cnt', 'request_type_id'])
  ->where(['YEAR(`request_date`)' => 2022])
  ->andWhere(['cancelled' => 0])
  ->groupBy('request_type_id')
  ->asArray()
  ->all();

  $countsByType = ArrayHelper::map($requestByType, 'request_type_id', 'cnt');
  echo 'Requests by Type';
  echo '<pre>';
  print_r($countsByType);
  echo '</pre>';

  $verificationByMonth = Requeststat::find()
  ->select(['COUNT(request_id) AS cnt', 'MONTH(`stat_date`) as month'])
  ->where(['YEAR(`stat_date`)' => 2022])
  ->andWhere(['status_id' => 30])
  ->groupBy('month')
  ->asArray()
  ->all();

  $countsVerificationByMonth = ArrayHelper::map($verificationByMonth, 'month', 'cnt');
  echo 'Request Verification by Month';
  echo '<pre>';
  //print_r($countsVerificationByMonth);
  echo '</pre>';

  $validationByMonth = Requeststat::find()
  ->select(['COUNT(request_id) AS cnt', 'MONTH(`stat_date`) as month'])
  ->where(['YEAR(`stat_date`)' => 2022])
  ->andWhere(['status_id' => 40])
  ->orWhere(['status_id' => 58])
  ->groupBy('month')
  ->asArray()
  ->all();

  $countsvalidationByMonth = ArrayHelper::map($validationByMonth, 'month', 'cnt');
  echo 'Request Validation by Month';
  echo '<pre>';
  //print_r($countsvalidationByMonth);
  echo '</pre>';

  $obligationByMonth = Requeststat::find()
  ->select(['COUNT(request_id) AS cnt', 'MONTH(`stat_date`) as month'])
  ->where(['YEAR(`stat_date`)' => 2022])
  ->andWhere(['status_id' => 55])
  ->groupBy('month')
  ->asArray()
  ->all();

  $countsobligationByMonth = ArrayHelper::map($obligationByMonth, 'month', 'cnt');
  echo 'Request Obligation by Month';
  echo '<pre>';
  //print_r($countsobligationByMonth);
  echo '</pre>';

  $disbursemenByMonth = Requeststat::find()
  ->select(['COUNT(request_id) AS cnt', 'MONTH(`stat_date`) as month'])
  ->where(['YEAR(`stat_date`)' => 2022])
  ->andWhere(['status_id' => 65])
  ->groupBy('month')
  ->asArray()
  ->all();

  $countsdisbursemenByMonth = ArrayHelper::map($disbursemenByMonth, 'month', 'cnt');
  echo 'Request Disbursement by Month';
  echo '<pre>';
  //print_r($countsdisbursemenByMonth);
  echo '</pre>';

  $approvalByMonth = Requeststat::find()
  ->select(['COUNT(request_id) AS cnt', 'MONTH(`stat_date`) as month'])
  ->where(['YEAR(`stat_date`)' => 2022])
  ->andWhere(['status_id' => 70])
  ->groupBy('month')
  ->asArray()
  ->all();

  $countsapprovalByMonth = ArrayHelper::map($approvalByMonth, 'month', 'cnt');
  echo 'Request Approval by Month';
  echo '<pre>';
  //print_r($countsapprovalByMonth);
  echo '</pre>';

  $users = User::find()
  // ->select(['COUNT(request_id) AS cnt', 'MONTH(`stat_date`) as month'])
  // ->where(['YEAR(`stat_date`)' => 2022])
  ->Where(['status' => 10])
  // ->groupBy('month')
  ->asArray()
  ->all();

  echo 'Users';
  echo '<pre>';
  //print_r($users);
  echo '</pre>';
  /*
  ini_set('memory_limit', '128M');
  $requests = Request::find()
  ->where(['YEAR(`request_date`)' => 2023])->orderBy(['request_date' => SORT_ASC])
  // ->where(['request_id' => 1])
  ->andWhere(['synced' => 0, 'cancelled' => 0])
  ->andWhere(['>=', 'status_id', 20])
  ->limit(1000)
  // ->offset(1)
  ->all();

  foreach($requests as $model){
    // $stat_arr = [];
    $block_arr = [];
    echo $model->request_number.' - '.$model->request_id;
    echo '<br/>';

    // Request Blocks
    $r_blocks = Blockchain::find()
      ->where(['index_id' => $model->request_id, 'scope' => 'Request'])
      ->orderBy(['status_id' => SORT_ASC])
      ->all();

    $last_status = 0;
    $last_timestamp = strtotime($model->request_date);
    foreach($r_blocks as $rb){
      $new_timestamp = $rb->timestamp;
      if($rb->status_id > $last_status){
        $block_arr = [
          'index_id' => $rb->index_id,
          'scope' => $rb->scope,
          'status_id' => $rb->status_id,
          'number_of_days' => Request::getDateDiff($last_timestamp, $new_timestamp, true),
          'block_date' => date('Y-m-d H:i:s',$rb->timestamp),
        ];

        $last_status = $rb->status_id;
        $last_timestamp = $rb->timestamp;

        if(!Requeststat::exist(
            $model->request_id, 
            $block_arr['status_id'])
          ){
          $stat = new Requeststat();
          $stat->request_id = $model->request_id;
          $stat->status_id = $block_arr['status_id'];
          $stat->number_of_days = $block_arr['number_of_days'];
          $stat->stat_date = $block_arr['block_date'];
          if($stat->save()){
            $stat->request->synced = 1;
            $stat->request->save();
          }
        }

        echo '<pre>';
        print_r($block_arr);
        echo '</pre>';
      }
    }

    
    // Osdv Blocks
    if($model->osdv){
      $o_blocks = Blockchain::find()
        ->where(['index_id' => $model->osdv->osdv_id, 'scope' => 'Osdv'])
        ->orderBy(['status_id' => SORT_ASC])
        ->all();

      foreach($o_blocks as $ob){
        $new_timestamp = $ob->timestamp;

        if($ob->status_id > $last_status){
          $block_arr = [
            'index_id' => $ob->index_id,
            'scope' => $ob->scope,
            'status_id' => $ob->status_id,
            'number_of_days' => Request::getDateDiff($last_timestamp, $new_timestamp, true),
            'block_date' => date('Y-m-d H:i:s',$ob->timestamp),
          ];

            $last_status = $ob->status_id;
            $last_timestamp = $ob->timestamp;

            if(!Requeststat::exist(
                $model->request_id, 
                $block_arr['status_id'])
              ){
              $stat = new Requeststat();
              $stat->request_id = $model->request_id;
              $stat->status_id = $block_arr['status_id'];
              $stat->number_of_days = $block_arr['number_of_days'];
              $stat->stat_date = $block_arr['block_date'];
              if($stat->save()){
                $stat->request->synced = 1;
                $stat->request->save();
              }
            }

          echo '<pre>';
          print_r($block_arr);
          echo '</pre>';
        }
      }
    }
  }*/

  // $start_date = date('Y-m-d', strtotime('2022-01-01'));
  // $end_date = date('Y-m-d', strtotime('2022-01-05'));
  /*
  $requests = Request::find()
    ->where(['YEAR(`request_date`)' => 2022])->orderBy(['request_date' => SORT_ASC])
    // ->where(['request_id' => 1])
    ->andWhere(['synced' => 0, 'cancelled' => 0, 'payroll' => 0])
    ->andWhere(['>=', 'status_id', 20])
    // ->andWhere(['not in', 'division_id', [2,4]])
    ->andWhere(['<>', 'division_id', 4])
    ->andWhere(['<>', 'request_type_id', 6])
    ->limit(20)
    // ->offset(1)
    ->all();
  
  foreach($requests as $model){
  
    echo $model->request_number.' - '.$model->request_id;
    echo '<br/>';
  
    switch ($model->obligation_type_id) {
        case 1:
          $status = [
              'submitted' => Request::submitted($model->request_id, $model->payroll, $model->status_id, true),
              'verified' => Request::verified($model->request_id, $model->payroll, $model->status_id, true),
              'validated' =>  Request::validated($model->request_id, $model->status_id, true),
              
              'certified_allotment' => 
                // Blockchain::find()
                //   ->where(['index_id' => $model->osdv->osdv_id, 'scope' => 'Osdv', 'status_id' => Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE])
                //   ->one() ?
                Request::certified_allotment(
                  $model->request_id, 
                  $model->osdv ? $model->osdv->osdv_id : NULL, 
                  $model->status_id, true),

              'allotted' => Request::allotted($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, $model->status_id, true),
              
              'certified_funds' => Request::certified_funds_Reg_Fund($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->status_id, true),
              'charged' => Request::charged($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->status_id, true),
              'approved' => Request::approved($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->status_id, true),
              // 'completed' => Request::completed($model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->status_id, true),
          ];  
          break;
          
        case 2:
          
          $status = [
              'submitted' => Request::submitted($model->request_id, $model->payroll, $model->status_id, true),
              'verified' => Request::verified($model->request_id, $model->payroll, $model->status_id, true),
              'validated' =>  Request::for_disbursement($model->request_id, $model->status_id, true),
              'certified_funds' => Request::certified_funds($model->request_id,  $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->obligation_type_id, $model->status_id, true),
              'charged' => Request::charged($model->request_id, 
                  $model->osdv ? $model->osdv->osdv_id : NULL, 
                  'Osdv', 
                  $model->status_id, true
              ),
              'approved' => Request::approved($model->request_id, $model->osdv->osdv_id, 'Osdv', $model->status_id, true),
              // 'completed' => Request::completed($model->osdv->osdv_id, 'Osdv', $model->status_id),
          ];   
          break;
            
        case 3:
          
          $status = [
              'submitted' => Request::submitted($model->request_id, $model->payroll, $model->status_id, true),
              'verified' => Request::verified($model->request_id, $model->payroll, $model->status_id, true),
              'validated' =>  Request::for_disbursement($model->request_id, $model->status_id, true),
              'certified_funds' => Request::certified_funds($model->request_id, $model->osdv ? $model->osdv->osdv_id : NULL, 'Osdv', $model->obligation_type_id, $model->status_id, true),
              'charged' => Request::charged($model->request_id,
                  $model->osdv ? $model->osdv->osdv_id : NULL, 
                  'Osdv', 
                  $model->status_id, true
              ),
              'approved' => Request::approved($model->request_id, $model->osdv->osdv_id, 'Osdv', $model->status_id, true),
              // 'completed' => Request::completed($model->osdv->osdv_id, 'Osdv', $model->status_id),
          ]; 
          break;
                    
        default:
          //code to be executed if n is different from all labels;
    }

    // foreach($status as $st){
    //   //print_r($st);
      
    //   if(!Requeststat::exist(
    //       $st['request_id'], 
    //       $st['status_id'])
    //     ){
    //     $stat = new Requeststat();
    //     $stat->request_id = $st['request_id'];
    //     $stat->status_id = $st['status_id'];
    //     $stat->number_of_days = $st['days'];
    //     if($stat->save()){
    //       $stat->request->synced = 1;
    //       $stat->request->save();
    //     }
    //   }
    // }
  }
  */



/* extract status_id from data field and save the value to status_id field
/*
  ini_set('memory_limit', '128M');
  $blocks = Blockchain::find()
  ->where(['status_id' => 0])
  ->limit(20000)
  ->all();
  // $count = 0;
foreach($blocks as $block){
  $blockStatus = (int)substr($block->data, -2); 
  
  $block->status_id = $blockStatus;
  $block->save();

  $last_block = $block->blockchain_id;
}
  echo $last_block;
 */
?>