<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\modules\pdfprint;
use common\components\MyPrint;
use yii\helpers\ArrayHelper;

$con =  Yii::$app->db;
$command = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`firstname`,' ', SUBSTR(`tbl_profile`.`middleinitial`,1,1),'. ', `tbl_profile`.`lastname`, '|' , `tbl_profile`.`designation`) AS employeename
        FROM `tbl_profile`");
$employees = $command->queryAll();
$command2 = $con->createCommand("SELECT * FROM `fais-procurement`.`tbl_supplier`");
$supplier = $command2->queryAll();

$listSupplier = ArrayHelper::map($supplier,'supplier_name','supplier_name');
$listEmployees = ArrayHelper::map($employees, 'employeename', 'employeename');

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Department */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="quotation-form">

    <?php //$form = ActiveForm::begin();

    ?>
    <?php $form = ActiveForm::begin(['action' => ['bids/report2'],'options' => ['method' => 'post','target'=>'_blank']]);
       echo $form->field($model,'purchase_request_id')->hiddenInput(['value'=>$model->purchase_request_id,'id'=>'id','name'=>'id'])->label(false);
    ?>
    <div class="form-group">
            <div class="row">
                <div class="col-lg-12-block">
                    <div class="col-lg-6">
                        <label>Supplier </label>
                        <?=
                        Select2::widget([
                            'data'=> $listSupplier,
                            'id' => 'cbosupplier',
                            'name' => 'cbosupplier',
                            'pluginEvents' => [
                                "change" => "function() {
                                                         var sup_id=$(this).val();
                                                
                                                        jQuery.ajax( {
                                                            type: \"POST\",
                                                            data: {
                                                                sup_id: sup_id,
                                                            },
                                                            url: \"/procurement/bids/checkimportid\",
                                                            dataType: \"text\",
                                                            success: function ( response ) {                              
                                                                data = $.parseJSON(response);
                                                                $.each(data, function(i, item) {
                                                                    var address = item.supplier_address;
                                                                    var supplier_id = item.supplier_id;
                                                                    $('#txtaddress').val(address);
                                                                    $('#txtsupplierid').val(supplier_id);
                                                                });
                                                            },
                                                            error: function ( xhr, ajaxOptions, thrownError ) {
                                                                alert( thrownError );
                                                            }
                                                        });
                                                     }",
                            ],
                        ])
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <label>PR Number </label>
                        <?= Html::label($model->purchase_request_number,'',['placeholder'=>'PR Number','class'=>'form-control','readonly'=>true]);?>
                    </div>
                </div>
            </div>
            <div class="space-10"></div>
            <div class="row">
                <div class="col-lg-12-block">
                    <div class="col-lg-6">
                        <label>Address </label>
                        <?= Html::hiddenInput('txtsupplierid','',['id'=>'txtsupplierid','class'=>'form-control']);?>
                        <?= Html::textarea('txtaddress','',['placeholder'=>'Address','id'=>'txtaddress','class'=>'form-control']);?>
                    </div>
                    <div class="col-lg-6">
                        <label>Date </label>
                        <?= Html::label( date("M j, Y", strtotime($model->purchase_request_date)),'',['placeholder'=>'Date','class'=>'form-control','readonly'=>true]);?>
                    </div>
                </div>
            </div>
            <div class="space-10"></div>
            <div class="row">
                <div class="col-lg-12-block">
                    <div class="col-lg-6">
                        <label>Submissions not Later than </label>
                        <?= Html::textInput('txtsubmission','',['placeholder'=>'Submission','class'=>'form-control','type'=>'date']);?>
                    </div>
                    <div class="col-lg-6">
                        <label>Supply Officer </label>
                        <?=
                        Select2::widget([
                            'data'=> $listEmployees,
                            'id' => 'cboemployees',
                            'name' => 'cboemployees'
                        ])
                        ?>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="lg-12-block">
                <div class="table-scroll">
                    <div id="a_page_loader" data-id="">
                        <div class="control-options false">
                            <div class="control-options-items">
                                <h1 id="tbl-item-selected"> selected</h1>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead id="myHead">
                            <tr>
                                <!--<td><div class="radio-container"><div id="main-radio" class="radio" <input type="radio" name="test" class="radio-ui"></td>-->
                                <td>Units</td>
                                <td>Item Description</td>
                                <td>Quantity</td>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $fin="";
                            foreach ($prdetails as $pr) {
                                $itemdescription = $pr["purchase_request_details_item_description"];
                                $quantity = $pr["purchase_request_details_quantity"];
                                $price = $pr["purchase_request_details_price"];
                                $totalcost =  $quantity * $price;
                                if ($quantity>1) {
                                    $unit = $pr["name_short"];
                                } else {
                                    $unit = $pr["name_long"];
                                }
                                $append = "<tr>";
                                //$append = $append . "<td><div class=\"radio-container\"><div class=\"radio tbl-tmt\" id=\"myitems\" data-id=\"\" data-radio=\"\"><input type=\"radio\" name=\"test\" class=\"radio-ui\"></div></div></td>";
                                $append = $append . "<td>". $unit ."</td>";
                                $append = $append . "<td>" . $itemdescription . "</td>";
                                $append = $append . "<td>" . $quantity . "</td>";
                                //$append = $append . "<td>" . $price . "</td>";
                                //$append = $append . "<td>" . $totalcost . "</td>";
                                $append = $append . "</tr>";
                                $fin = $fin . $append;
                            }
                            ?>
                            <?= $fin ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-10">
        </div>
        <div class="col-lg-2">
            <?= Html::submitButton( 'Print' , ['class' =>  'btn-pdfprint btn btn-primary btn-block','id'=> 'btnSubmit','name'=>'btnSubmit','data-pjax'=>'0']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php //echo pdfprint\Pdfprint::widget(['elementClass' => '.btn-pdfprint']); ?>
</div>


