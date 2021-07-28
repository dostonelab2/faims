<?php

/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/13/2018
 * Time: 9:47 AM
 */


use yii\helpers\Html;
use common\modules\pdfprint;

use common\components\Functions;
use yii2mod\alert\Alert;




$func = new Functions();

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\PurchaserequestSearch */
/* @var $model common\models\procurement\Lineitembudget */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'Purchase Request';
$angularcontroller = "";
$this->params['breadcrumbs'][] = '';
//$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
$this->registerJsFile($BaseURL . 'js/angular.min.js');
//$this->registerJsFile($BaseURL . 'js/ui-bootstrap-tpls-0.10.0.min.js');
$this->registerJsFile($BaseURL . 'js/ui-bootstrap-custom-tpls-2.5.0.min.js');
$this->registerJsFile($BaseURL . 'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL . 'js/app.js');
$this->registerJsFile($BaseURL . 'js/custom.js');

//echo Yii::$app->user->identity->id;
?>


<div class="request-index">
    <h1 class="centered"><i class="fa fa-cart-plus"></i> <?= Html::encode($this->title) ?></h1>
    <h5><a id="startButton" href="javascript:void(0);"><img src="<?= $BaseURL; ?>images\help.png" height="52" width="98" style="padding: 10px;"></a></h5>
    <?php
    //Generate Header Controller AngularJS
    $maincontroller = str_replace(" ", "", strtolower(Html::encode($this->title))) . "ctrl"; ?>
    <?=
    //Generate AngularJS Header
    $func->GridHeaderAngularJS($maincontroller, "myAdd", "Create New Purchase Request"); ?>
    </h5>
    <?= $func->GridHeader('Request #', 'purchase_request_number'); ?>
    <?= $func->GridHeader('Request Purpose ', 'purchase_request_purpose'); ?>
    <?= $func->GridHeader('Division ', 'division_name'); ?>
    <?= $func->GridHeader('Requested By ', 'requested_by'); ?>
    <?= $func->GridHeader('PO # ', 'PONum'); ?>
    <?= $func->GridHeader('Status', 'default'); ?>
    <?= $func->GridHeader('Action', ''); ?>
    <?= //Close The AngularJS Header
    $func->GridHeaderAngularJSClose();
    ?>
    <!-- *********************************** Generate Header Grid Details ************************************************ -->
    <?=
    $func->GridHeaderDetails();
    ?>
    <!-- *********************************** Generate Grid Details ************************************************ -->
    <?= $func->GridDetails('purchase_request_number');  ?>
    <?= $func->GridDetails('purchase_request_purpose'); ?>
    <?= $func->GridDetails('division_name');  ?>
    <?= $func->GridDetails('requested_by');  ?>
    <?= $func->GridDetails('PONum');  ?>
    <td width="20%">
        <div ng-if="data.status == 0">
            <span class="label label-warning">Created</span>
        </div>
        <div ng-if="data.status == 1">
            <span class="label label-success" uib-popover="{{data.request_status == 2 ? 'Awarded' : 'Ongoing'}}" popover-trigger="'mouseenter'" popover-title="Request Status:">Approved</span>
        </div>
        <div ng-if="data.status == 2">
            <span class="label label-danger">Disapproved</span>
        </div>
        <div ng-if="data.status == 3">
            <span class="label label-info">Reviewed</span>
        </div>
    </td>

    <!-- *********************************** Start Group for Buttons ************************************************ -->

    <?= $func->GridGroupStart('button-control') ?>
    <?= $func->GridButton('purchase_request_id', "", "btnView", "primary", "", "grdbutton", "fa fa-eye", "myView", "myView") ?>
    <?= $func->GridButton('purchase_request_id', "", "btnEdit", "default ", "", "grdbutton", "fa fa-edit", "Update", "myEdit") ?>
    <h5 style='display: inline-block;margin:0px;' data-step='5' data-intro='Click here to Report'><a href="reportprfull?id={{data.purchase_request_id}}" class="btn-pdfprint btn btn-warning grdbutton"> <i class="fa fa-print"></i></a></h5>
    <h5 ng-if="data.status == 0 || data.status == 3" style="display: inline-block;margin:0px;" data-step="2" data-intro="Click here to Tag"><a type="button" title="status tagging" data-target="#myTagging" data-toggle="modal" data-id="{{data.purchase_request_id}}" class="myTagging btn btn-success  grdbutton"> <i class="fa fa-tag"></i></a></h5>
    <?= $func->GridGroupEnd(); ?>
    <!-- *********************************** Close Group for Buttons ************************************************ -->
    <?=
    $func->GridHeaderClose();
    ?>
    <!-- *********************************** Close Grid Details ************************************************ -->

    <!-- *********************************** Generate Header Modal for Create ************************************************ -->
    <?= $func->GenerateHeaderModal("myAdd", "Request Module", '65', 0) ?>
    <div class="request-create">
        <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="mycreate">
        </div>
    </div>
    <?=
    $func->GenerateFooterModal("Close", "Proceed", 0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Generate Header Modal for Create ************************************************ -->
    <?= $func->GenerateHeaderModal("Update", "Request Module", '60', 0) ?>
    <div class="request-update">
        <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="mycontent">
        </div>
    </div>
    <?=
    $func->GenerateFooterModal("Close", "Proceed", 0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Generate Header Modal for View ************************************************ -->
    <?= $func->GenerateHeaderModal("myView", "Request Module", '70', 10) ?>
    <div class="request-view">
        <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="mycontentview">
        </div>
    </div>
    <?=
    // This function will close the footer of the modal
    $func->GenerateFooterModal("Close", "Proceed", 0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Generate Header Modal for tagging********************************************** -->
    <?= $func->GenerateHeaderModal("myTagging", "Request Module", '70', 10) ?>
    <div class="request-tag">
    <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="mycontenttag">
        </div>
    </div>
    <?=
    // This function will close the footer of the modal
    $func->GenerateFooterModal("Close", "Proceed", 0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Close for View ************************************************ -->
    <?php
    // This section will allow to popup a notification
    $session = Yii::$app->session;
    if ($session->isActive) {
        $session->open();
        if (isset($session['deletepopup'])) {
            $func->CrudAlert("Deleted Successfully", Alert::TYPE_WARNING);
            unset($session['deletepopup']);
            $session->close();
        }
        if (isset($session['updatepopup'])) {
            $func->CrudAlert("Updated Successfully");
            unset($session['updatepopup']);
            $session->close();
        }
        if (isset($session['savepopup'])) {
            $func->CrudAlert("Saved Successfully", Alert::TYPE_SUCCESS, true);
            unset($session['savepopup']);
            $session->close();
        }
        if (isset($session['errorpopup'])) {
            $func->CrudAlert("Error Transaction", Alert::TYPE_WARNING, true);
            unset($session['errorpopup']);
            $session->close();
        }
    }
    ?>
    <?= pdfprint\Pdfprint::widget([
        'elementClass' => '.btn-pdfprint'
    ]); ?>

</div>

<script type="text/javascript">
    document.getElementById('startButton').onclick = function() {
        introJs().setOption('doneLabel', 'Next page').start().oncomplete(function() {
            //$("#buttonAddObligation").click();
            $(".myAdd").click();
        });
    };
</script>