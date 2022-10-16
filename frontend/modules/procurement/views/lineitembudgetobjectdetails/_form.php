<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;

use common\models\procurement\Objectdetail;
use common\models\procurement\Objectdetailcategory;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudgetobjectdetails */
/* @var $form yii\widgets\ActiveForm */

$ids=$id;

$randomsss = rand(10,500000);

$this->registerJs(
    'jQuery(document).ready(function($){
                $(document).ready(function () {
                
                    var saveButtons = $("#btnSave");
                    var randomsss =  "'.$randomsss.'";
                    if(saveButtons.attr("disabled")) {
                    }else{
                    $("body").on("submit", "form#objectdetails-form-'.$randomsss.'", function (e) {
                            $("#btnSave").val("saving...");
                            $("#btnSave").prop("disabled",true);
                            e.preventDefault();
                            var form = $(this);
                            var formData = form.serialize();
                            var ids = "'.$ids.'";
                            $.ajax({
                                url: form.attr("action"),
                                type: form.attr("method"),
                                data: formData,
                                success: function (data) { 
                                            $("#modalObject").modal("toggle");
                                            $.pjax.reload({container:"#lineitembudget-details-"+ids+"-pjax"}); //for pjax update 
                        },
                                error: function () {
                                    alert("Something went wrong");
                                }
                            });
                            return false;                       
                    });
                    }
                    
               });
    });');
?>
<div class="lineitembudgetobjectdetails-form">

    <?php $form = ActiveForm::begin([
        'id' => 'objectdetails-form-'.$randomsss,
        'enableClientValidation' => true,
        //'enablePushState' => false,
        'options' => ['data-pjax' => true,
    ]]); ?>

    <?= $form->field($model, 'line_item_budget_object_id')->hiddenInput(['value'=>$id])->label(false) ?>

    <!--?= $form->field($model, 'object_detail_id')->textInput() ?-->
    
    <?php     
    echo $form->field($model, 'object_detail_category_id')->widget(Select2::classname(), [
        'data' => $objectdetailscategory,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Object ...', 'id'=>'object-detail-category-id'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    
    <?= $form->field($model, 'object_detail_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'object_detail_id'],
                    'pluginOptions'=>[
                        'depends'=>['object-detail-category-id'],
                        'placeholder'=>'Select Object',
                        'url'=>Url::to(['listobjectdetails'])
                    ]
                ]); 
    ?>
    
    <?php
    /*echo $form->field($model, 'object_detail_id')->widget(Select2::classname(), [
        'data' => $objectdetails,
        'language' => 'de',
        'options' => ['placeholder' => 'Select Object ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);*/
    
    ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'details')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=> 'btnSave']) ?>
    </div>

    <?php ActiveForm::end();
    ?>
</div>