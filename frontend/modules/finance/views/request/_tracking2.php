<div class="container" style="border: 1px solid #000; padding-top: 15px;">
    <div class="row justify-content-md-center">
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['submitted']['status'] ?>" >
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forVerification ?--></h3>

              <p>SUBMITTED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['submitted']['date'] ?>
               <?php 
//                    $date_submitted = new DateTime( date("Y-m-d H:i:s", $status['submitted']['date']) );
//                    $date_submitted = $date_submitted->format("Y-m-d H:i:s");
//                    echo $date_submitted;
               ?> 
<!--                <i class="fa fa-arrow-circle-right"></i>-->
            </a>
          </div>
        </div>   
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['verified']['status'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forVerification ?--></h3>

              <p>VERIFIED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['verified']['days'] ?> 
<!--            <i class="fa fa-arrow-circle-right"></i>-->
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['validated']['status'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forValidationFASS.' - '.$forValidationFOS ?--></h3>

              <p>VALIDATED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['validated']['days'] ?> 
<!--            <i class="fa fa-arrow-circle-right"></i>-->
            </a>
          </div>
        </div>
        
        <?php if($model->obligation_type_id == 1) { ?>
        <!-- ./col -->
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['certified_allotment']['status'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forAllotment ?--></h3>

              <p>CERTIFIED ALLOTMENT</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['certified_allotment']['days'] ?>  
<!--            <i class="fa fa-arrow-circle-right"></i>-->
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['allotted']['status'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forAllotment ?--></h3>

              <p>ALLOTTED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['allotted']['days'] ?>  
<!--            <i class="fa fa-arrow-circle-right"></i>-->
            </a>
          </div>
        </div>
        
        <?php } ?>

        <!-- ./col -->
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['charged']['status'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forDisbursement ?--></h3>

              <p>CHARGED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="icon icon-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['charged']['days'] ?>

            </a>
          </div>
        </div>
        
        <?php if($model->payroll) { ?>
        <!-- ./col -->
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['approved_partial'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forApproval ?--></h3>

              <p>APPROVED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="icon icon-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <?php } else { ?>
        <!-- ./col -->
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['approved']['status'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forApproval ?--></h3>

              <p>APPROVED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="icon icon-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['approved']['days'] ?>
<!--             <i class="fa fa-arrow-circle-right"></i>-->
             </a>
          </div>
        </div>
        
        <?php } ?>
        
        <!-- ./col -->
        <div class="col-md-2">
          <!-- small box -->
          <div class="small-box <?= $status['completed']['status'] ?>">
            <div class="inner" style="text-align: center;">
              <h3><!--?= $forPayment ?--></h3>

              <p>COMPLETED<br/>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="icon icon-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer"><?= $status['completed']['days'] ?>
<!--             <i class="fa fa-arrow-circle-right"></i>-->
             </a>
          </div>
        </div>
        <!-- ./col -->
    </div>
</div>