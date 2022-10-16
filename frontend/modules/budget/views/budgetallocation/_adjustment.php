<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

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
            /*'deleteOptions'=>[ // your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete'=>true],
            ],*/
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            'buttons1' => '',//( (Yii::$app->user->identity->username == 'Admin') || $model->owner() ) ? '{update}' : '', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            //'formOptions' => ['action' => ['request/view', 'id' => $model->request_id]],
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>$budgetallocationitem->name,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
        ]); ?>
</div>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--?= $form->field($model, 'request_number')->textInput() ?-->

    <!--?= $form->field($model, 'request_date')->textInput() ?-->
    
    <div class="row">
        <div class="col-md-6"> 
                <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Requesttype::find()->where('active =:active',[':active'=>1])->all(),'request_type_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Request Type','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    'pluginEvents'=>[
                        "change" => 'function() { 
                            var requestTypeId=this.value;
                            $.post("/finance/request/updateparticulars/", 
                                {
                                    requestTypeId: requestTypeId
                                }, 
                                function(response){
                                    if(response){
                                       $("#request-particulars").val(response.default_text);
                                       //alert(response.default_text);
                                    }
                                }
                            );
                        }
                    ',]
                ])->label('Request Type'); ?>
        </div>
    </div>
    

    <?= $form->field($model, 'payee_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Creditor::find()->orderBy(['name'=>SORT_ASC])->all(),'creditor_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Payee','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Payee / Creditor'); ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>