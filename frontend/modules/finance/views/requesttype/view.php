<?php

//use yii\helpers\Html;
//use yii\helpers\Url;

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\form\ActiveForm;
use kartik\grid\GridView;

use common\models\finance\Attachment;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Request */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Request Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <?php $attributes = [
            [
                'group'=>true,
                'label'=>'Details',
                'rowOptions'=>['class'=>'info'],
                'valueColOptions'=>['style'=>'width:30%']
            ],
            [
                'attribute'=>'name',
                'label'=>'Name',
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'attribute'=>'default_text',
                'label'=>'Default Text',
                'inputContainer' => ['class'=>'col-sm-6'],
                //'value' => $model->creditor->name,
            ],
            [
                'group'=>true,
                'label'=>'Required Documents',
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'attribute'=>'documents',
                'label'=>'',
                'format'=>'raw',
                'value'=>function() use ($model){
                    $text = '';
                    //foreach($model->documents as $doc){
                    for($i=0;$i<count($model->documents);$i++){
                        //$text .= Attachment::find($model->documents[2])->one()->name.'<br/>';
                        //$text .= ' '.'<span class="label label-primary">'.$rqa->attachment->name.'</span>';
                        $t = Attachment::findOne($model->documents[$i]);
                        $text .= $t->name.'<br/>';
                    }
                    return $text;
                },
                'inputContainer' => ['class'=>'col-sm-6'],
                'type'=>DetailView::INPUT_CHECKBOX_LIST,
                'items'=>ArrayHelper::map(Attachment::find()->orderBy('name')->asArray()->all(), 'attachment_id', 'name'),
            ],

        ];?>
        
    <?php
        echo DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            /*'deleteOptions'=>[ // your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete'=>true],
            ],*/
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            'buttons1' => '{update}', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'formOptions' => ['action' => ['requesttype/view1', 'id' => $model->request_type_id]],
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'<i class="glyphicon glyphicon-book"></i> REQUEST TYPE',
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
            
        ]);
    ?>
</div>
<script type="text/javascript">
$( document ).ready(function() {
    $('input[name^=Requesttype]').change(function(){ onCheck(this.value,$(this).prop('checked')); });
});    
    
    
function onCheck(attachment_id,checked){
    var request_type_id = <?php echo $model->request_type_id?>;
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['requesttype/addattachment']); ?>",
            data: {requestTypeId:request_type_id,attachmentId:attachment_id,checked:checked},
            success: function(data){ 
                }
            });
    return false;
  //});
}    
</script>