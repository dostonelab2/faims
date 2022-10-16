<?php

use yii\helpers\Html;

use common\components\Functions;
use common\models\procurement\Department;
use frontend\modules\procurement\controllers\DepartmentController;
use yii2mod\alert\Alert;


$func = new Functions();

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Department */
/* @var $searchModel common\models\procurement\Department */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'Departments';
$angularcontroller = "";
$this->params['breadcrumbs'][] = "";
$this->registerJsFile($BaseURL.'js/angular.min.js');
$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
$this->registerJsFile($BaseURL.'js/app.js');

?>
<div class="department-index">

    <h1 class="centered"><i class="fa fa-sitemap"></i> <?= Html::encode($this->title) ?></h1>
    <?php
    //Generate Header Controller AngularJS
    $maincontroller=str_replace(" ", "",strtolower(Html::encode($this->title)))."ctrl";?>
    <?=
    //Generate AngularJS Header
    $func->GridHeaderAngularJS($maincontroller,"myAdd","Add Department");?>
    <?= $func->GridHeader('Department Name','department_name'); ?>
    <?= $func->GridHeader('Department Description','department_desc'); ?>
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
    <?= $func->GridDetails('department_name');  ?>
    <?= $func->GridDetails('department_desc');  ?>
    <!-- *********************************** Start Group for Buttons ************************************************ -->
    <?= $func->GridGroupStart('button-control')?>

    <?= $func->GridButton('department_id',"","btnDelete","danger","","grdbutton","fa fa-minus","Delete","procurement/department/") ?>
    <?= $func->GridButton('department_id',"","btnEdit","default ","","grdbutton","fa fa-edit","Update","myEdit") ?>
    <?= $func->GridButton('department_id',"","btnView","primary","","grdbutton", "fa fa-eye","myView","myView") ?>

    <?= $func->GridGroupEnd();?>
    <!-- *********************************** Close Group for Buttons ************************************************ -->
    <?=
    $func->GridHeaderClose();
    ?>
    <!-- *********************************** Close Grid Details ************************************************ -->



    <!-- *********************************** Generate Header Modal for Create ************************************************ -->
    <?= $func->GenerateHeaderModal("myAdd","Department Module",'23',10) ?>
    <div class="department-create">
        <div class="loadpartial">
            <img src="<?= $BaseURL ?>/images/loading.gif">
        </div>

        <div id="mycreate">

        </div>


    </div>
    <?=
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Generate Header Modal for Create ************************************************ -->
    <?= $func->GenerateHeaderModal("Update","Department Module",'23',10) ?>
    <div class="department-update">

        <div class="loadpartial">
            <img src="<?= $BaseURL ?>/images/loading.gif">
        </div>

        <div id="mycontent">

        </div>

    </div>

    <?=
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->


    <!-- *********************************** Generate Header Modal for View ************************************************ -->
    <?= $func->GenerateHeaderModal("myView","Department Module",'23',10) ?>
    <div class="department-view">

        <div class="loadpartial">
            <img src="<?= $BaseURL ?>/images/loading.gif">
        </div>

        <div id="mycontentview">

        </div>

    </div>

    <!-- *********************************** Close for View ************************************************ -->

    <?php
    $session = Yii::$app->session;
    if ($session->isActive) {
        $session->open();
        if (isset($session['deletepopup'])) {
            $func->CrudAlert("Deleted Successfully",Alert::TYPE_ERROR);
            unset($session['deletepopup']);
            $session->close();
        }
        if (isset($session['updatepopup'])) {
            $func->CrudAlert("Updated Successfully");
            unset($session['updatepopup']);
            $session->close();
        }
        if (isset($session['savepopup'])) {
            $func->CrudAlert("Saved Successfully",Alert::TYPE_SUCCESS);
            unset($session['savepopup']);
            $session->close();
        }
    }
    ?>


    <?=
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

</div>
