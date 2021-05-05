<?php
use yii\helpers\Html;


use common\components\Functions;

$func = new Functions();


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'Quotation Bids and Awards';
$angularcontroller = "";
$this->params['breadcrumbs'][] = '';
$this->registerJsFile($BaseURL.'js/angular.min.js');
$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/procurement/bids/bids.js');
$this->registerJsFile($BaseURL.'js/custom.js');

echo $BaseURL;
?>

<div class="request-index">
    <h1 class="centered"><i class="fa fa-sitemap"></i> <?= Html::encode($this->title) ?></h1>

    <!-- Panel Start -->
    <h5><a id="startButton"  href="javascript:void(0);"><img src="<?= $BaseURL;?>images\help.png" height="52" width="98" style="padding: 10px;"></a></h5>

    <?php
    /* Generate Header Controller AngularJS
    */
    $maincontroller=str_replace(" ", "",strtolower(Html::encode($this->title)))."ctrl";
    ?>

    <?=
    /*Generate AngularJS Header
         GridHeaderAngularJS(theAngularControllername,linktarget);
   */
    $func->GridHeaderAngularJS($maincontroller,"myAdd");?>
    <?php
    /*
         GridHeader(Headertitle,Sortfields);
   */
    ?>
    <?= $func->GridHeader('Request #','purchase_request_number'); ?>
    <?= $func->GridHeader('Request Purpose','purchase_request_purpose'); ?>
    <?= $func->GridHeader('Division ','division_name'); ?>
    <?= $func->GridHeader('Section ','section_name'); ?>
    <?= $func->GridHeader('Action ',''); ?>
    <?= //Close The AngularJS Header
    $func->GridHeaderAngularJSClose();
    ?>
    <!-- *********************************** Open Header Grid Details ************************************************

    -->
    <?=
    $func->GridHeaderDetails();
    ?>
    <!-- *********************************** Oprn Grid Details ************************************************

        GridDetails(datavalue)

     -->
    <?= $func->GridDetails('purchase_request_number');  ?>
    <?= $func->GridDetails('purchase_request_purpose'); ?>
    <?= $func->GridDetails('division_name');  ?>
    <?= $func->GridDetails('section_name');  ?>
    <!-- *********************************** Open Group for Buttons ************************************************
        GridButton(datavalue,buttontitle,buttonid,buttontype,buttonblock="",css,fa-icon,buttonname,moduleURL)
    -->
    <?= $func->GridGroupStart('button-control')?>
    <h5 style='margin:0px;' data-step='1' data-intro='Proceed to Bids And Awards'><span>
    <?= $func->GridButton('purchase_request_id',"Bids And Awards","btnBidsandAwards","success ","","grdbutton","fa fa-industry","bids","bids") ?>
    </span>
    </h5>
    <h5 style='margin:0px;' data-step='2' data-intro='Proceed to Request for Quotation'><span>
    <?= $func->GridButton('purchase_request_id',"Quotation","btnQuotation","warning ","","grdbutton","fa fa-paperclip","quotation","quotation") ?>
    </span>
    </h5>
    <?= $func->GridGroupEnd();?>
    <!-- *********************************** Close Group for Buttons ************************************************ -->
    <?=
    $func->GridHeaderClose();
    ?>
    <!-- *********************************** Close Header Grid Details ************************************************ -->

    <!-- *********************************** Generate Header Modal for Create ************************************************
                            GenerateHeaderModal (id,title,widthsize,topheight)
    -->
    <?= $func->GenerateHeaderModal("quotation","Request For Quotation",'55',6) ?>
    <div class="request-quote">
        <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        </div>
    </div>
    <div id="quotationview">
    <?=
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Close for View ************************************************
                            GenerateFooterModal(title,nextbuttiontitle,allowbutton=booloean)
     -->


    <!-- *********************************** Generate Header Modal for Create ************************************************
                        GenerateHeaderModal (id,title,widthsize,topheight)
-->
    <?= $func->GenerateHeaderModal("bids","Abstract of Bids",'80',2) ?>
    <div class="request-bids">
        <div class= "loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="bidsview">
        </div>
    </div>
    <?=
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Close for View ************************************************
                            GenerateFooterModal(title,nextbuttiontitle,allowbutton=booloean)
     -->

</div>





<script type="text/javascript">
    document.getElementById('startButton').onclick = function() {
        introJs().setOption('doneLabel', 'Next page').start().oncomplete(function() {
            //window.location.href = 'index?multipage=true';
        });
    };
</script>