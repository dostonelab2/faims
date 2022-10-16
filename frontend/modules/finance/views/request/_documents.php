<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\editable\Editable;
use kartik\grid\GridView;

use common\models\finance\Requestattachment;
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attachment-info">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'attachment-info'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Physical Documents Checklist</h1>    
        <p class="md-info">Please ensure that all physical documents are complete and in order.<br/>Kindly double check if the physical documents are signed if needed.</p><br/>
       
      <br/><p>Note: Only requests with complete documents can be submitted for processing.</p>
    </div>
    
    
    <?php
        $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '10px',
                    'header' => '',
                    //'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                
                [
                    'attribute' => 'request_attachment_id',
                    'label' => 'Name',
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->attachment->name;
                            },
                ],
                [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'name'=>'Original Document available', 
                    'name'=>'request-attachments', //additional
                    'checkboxOptions' => function($model, $key, $index, $column){
                                             return ['checked' => ($model->original_doc_status_id ==  10 ) ? true : false,
                                                    'onclick'=>'onRequestAttachment(this.value,this.checked)' //additional
                                                    //'onclick'=>'alert(this.value)' //additional
                                                    ];
                                         }
                ],
            ];
    ?>
    <?= GridView::widget([
                'id' => 'lddap-ada-items',
                'dataProvider' => $attachmentsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">CREDITORS</h3>',
                    'type'=>'primary',
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=> '',
                                    ],
                                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
                'itemLabelSingle' => 'item',
                'itemLabelPlural' => 'items'
            ]);
    
    ?>


<script type="text/javascript">
function onRequestAttachment(request_attachment_id,checked){
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['requestattachment/markonhand']); ?>",
            data: {request_attachment_id:request_attachment_id,checked:checked},
            success: function(data){ 
                }
            });
    return false;
}        
</script>
    
    
    <div class="form-group">
        <?= Html::submitButton('TURNOVER DOCUMENTS', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
