<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use common\models\budget\Budgetallocationitem;
/* @var $this yii\web\View */
/* @var $model common\models\budget\Allocationadjustment */
/* @var $form yii\widgets\ActiveForm */
/*
$reqs =  Requestextend::find()->select(['request_id'])->where(['DATE_FORMAT(request_datetime, "%Y-%m")' => $yearmonth,'lab_id'=>$lab_id])->andWhere(['>','status_id',0])->with(['samples' => function($query){
                $query->andWhere(['active'=>'1']);
            }])->all();
*/
//$item = Budgetallocationitem::find()->where(['expenditure_class_id'=>1])->with(['budgetallocation' => function($query) use ($model){
//    $query->andWhere(['budget_allocation_id' => $model->budget_allocation_id]);
//}])->one();

?>

<div>
    <?php $attributes = [
            [
                'attribute'=>'budget_allocation_id',
                'label'=>'Section',
                'value' => $budgetallocationitem->budgetallocation->section->name,
                
                'inputContainer' => ['class'=>'col-sm-6'],
                'displayOnly'=>true
            ],
            [
                'attribute'=>'amount',
                'label'=>'Amount (P)',
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
                'displayOnly'=>true
            ],
            
        ];?>
    <?= DetailView::widget([
            'model' => $budgetallocationitem,
            'mode'=>DetailView::MODE_VIEW,
            'container' => ['id'=>'kv-demo'],
            
            'buttons1' => '',
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>$budgetallocationitem->name,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
        ]); ?>
</div>



<div class="allocationadjustment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
            echo $form->field($model,'destination_id')->widget(Select2::classname(),[
                'data' => $sections,
                'theme' => Select2::THEME_KRAJEE,
                'options' => [
                    'placeholder' => 'Select Section',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    "change" => "function() {
                        var sectionId = this.value;
                        $.ajax({
                            url: '".Url::toRoute("/budget/allocationadjustment/expenditures")."',
                            //dataType: 'json',
                            method: 'POST',
                            data: {id:sectionId},
                            //data: {id:sectionId, budgetAllocationId:budgetAllocationId, budgetAllocationItemId:budgetAllocationItemId, sourceId:sourceId},
                            success: function (data, textStatus, jqXHR) {
                                $('#expenditures').html(data);
                            },
                            beforeSend: function (xhr) {
                                $('.image-loader').addClass( \"img-loader\" );
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus+' '+errorThrown);
                            }
                        });
                    }",
                ],
            ]);
            ?>
    <div class="row">
            <div class="col-lg-12">  
            <div class="row">
            <div class="col-lg-12">  
                <div id="expenditures" style="padding:0px!important;">    	
                  
                </div> 
            </div>
            </div> 
            </div>
        </div> 
    <?= $form->field($model, 'amount')->textInput(['value'=>$budgetallocationitem->amount, 'type'=>'number', 'max'=>$budgetallocationitem->amount]) ?>
    <?= $form->field($model, 'source_item')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'new_item')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'item_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'item_detail_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
