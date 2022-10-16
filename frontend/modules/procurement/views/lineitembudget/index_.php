<?php

use yii\helpers\Html;

use common\components\Functions;
use common\models\procurement\Lineitembudget;
use frontend\modules\procurement\controllers\LineitembudgetController;
use yii2mod\alert\Alert;


$func = new Functions();

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Department */
/* @var $searchModel common\models\procurement\Department */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'Lineitembudget';
$angularcontroller = "";
$this->params['breadcrumbs'][] = "";
$this->registerJsFile($BaseURL.'js/angular.min.js');
$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/lineitembudget/expenditureclass.js');

?>
<div class="department-index">

    <h1 class="centered"><i class="fa fa-sitemap"></i> <?= Html::encode($this->title) ?></h1>
    <?php
    //Generate Header Controller AngularJS
    $maincontroller=str_replace(" ", "",strtolower(Html::encode($this->title)))."ctrl";?>
    
    <?=
    //Generate AngularJS Header
    $func->GridHeaderAngularJS($maincontroller,"myAdd","Add Line Item Budget");?>
     
    <?= $func->GridHeader('Type','type'); ?>
    <?= $func->GridHeader('Title','title'); ?>
    <?= $func->GridHeader('Period','period'); ?>
    <?= $func->GridHeader('Duration Start','duration_start'); ?>
    <?= $func->GridHeader('Duration End','duration_end'); ?>
    <?= $func->GridHeader('',''); ?>
    <?=
    //Close The AngularJS Header
    $func->GridHeaderAngularJSClose();
    ?>
    
    <!-- *********************************** Generate Header Grid Details ************************************************ -->
    <?=
    $func->GridHeaderDetails();
    ?>
    <!-- *********************************** Generate Grid Details ************************************************ -->
    <?= $func->GridDetails('type');  ?>
    <?= $func->GridDetails('title');  ?>
    <?= $func->GridDetails('period');  ?>
    <?= $func->GridDetails('duration_start');  ?>
    <?= $func->GridDetails('duration_end');  ?>

    <!-- *********************************** Start Group for Buttons ************************************************ -->
    <?= $func->GridGroupStart('button-control')?>

    <?= $func->GridButton('line_item_budget_id',"","btnDelete","danger","","grdbutton","fa fa-minus","Delete","procurement/lineitembudget/") ?>
    <?= $func->GridButton('line_item_budget_id',"","btnEdit","default ","","grdbutton","fa fa-edit","Update","myEdit") ?>
    <?= $func->GridButton('line_item_budget_id',"","btnView","primary","","grdbutton", "fa fa-eye","myView","myView") ?>

    <?= $func->GridGroupEnd();?>
    <!-- *********************************** Close Group for Buttons ************************************************ -->
    <?=
    $func->GridHeaderClose();
    ?>
    <!-- *********************************** Close Grid Details ************************************************ -->


    <!-- *********************************** Create Modal : Start ******************************************* -->
    <!-- * $func->GenerateHeaderModal('modal ID', 'modal Title', 'form-width (%)', 'top position (%)') ****** -->
    <?= $func->GenerateHeaderModal("myAdd","Line Item Budget Module",'80',3) ?>
    
    
    
    <div class="lineitembudget-create">
        <div class="loadpartial">
            <img src="<?= $BaseURL ?>/images/loading.gif">
        </div>

        <div id="mycreate">

        </div>


    </div>
    
    
    <?= $func->GenerateFooterModal("Close","Proceed",0); ?>
    <!-- *********************************** Create Modal : End ******************************************* -->
    
    
    <!-- *********************************** Update Modal : Start ******************************************* -->
    <!-- * $func->GenerateHeaderModal('modal ID', 'modal Title', 'form-width (%)', 'top position (%)') ****** -->
    <?= $func->GenerateHeaderModal("Update","Department Module",'23',10) ?>
    <div class="department-update">

        <div class="loadpartial">
            <img src="<?= $BaseURL ?>/images/loading.gif">
        </div>

        <div id="mycontent">

        </div>

    </div>
    
    <?= $func->GenerateFooterModal("Close","Proceed",0); ?>
    <!-- *********************************** Update Modal : End ******************************************* -->

    
    
    <!-- *********************************** View Modal : Start ******************************************* -->
    <!-- * $func->GenerateHeaderModal('modal ID', 'modal Title', 'form-width (%)', 'top position (%)') ****** -->
    <?= $func->GenerateHeaderModal("myView","Department Module",'23',10) ?>
    <div class="department-view">

        <div class="loadpartial">
            <img src="<?= $BaseURL ?>/images/loading.gif">
        </div>

        <div id="mycontentview">

        </div>

    </div>
    
    <?= $func->GenerateFooterModal("Close","Proceed",0); ?>
    <!-- *********************************** Update Modal : End ******************************************* -->

    <!-- *********************************** Alert Modal : Start ******************************************* -->
    <?php
    $session = Yii::$app->session;
    if ($session->isActive) {
        $session->open();
        if (isset($session['deletepopup'])) {
            $func->CrudAlert("Deleted Successfully","WARNING");
            unset($session['deletepopup']);
            $session->close();
        }
        if (isset($session['updatepopup'])) {
            $func->CrudAlert("Updated Successfully");
            unset($session['updatepopup']);
            $session->close();
        }
        if (isset($session['savepopup'])) {
            $func->CrudAlert("Saved Successfully");
            unset($session['savepopup']);
            $session->close();
        }
    }
    ?>
    <!-- *********************************** Alert Modal : End ******************************************* -->
</div>
