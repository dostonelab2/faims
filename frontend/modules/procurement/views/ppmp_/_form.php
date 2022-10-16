<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\procurement\Ppmp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppmp-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
        'data' => $listDivisions,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Division'],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'charged_to')->widget(Select2::classname(), [
        'data' => $chargeTo,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Charging'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [
            "change" => 'function(data) { 
                charging = $(this).val();
                if(charging == 1){
                    $("#ppmp-project_id").show();
                    alert("hahahhaa");
                }else{
                    $("#ppmp-project_id").show();
                }
            }',
        ],
    ]); ?>

    <?= $form->field($model, 'project_id')->widget(Select2::classname(), [
        //'id' => 'ppmp_project_id',
        'data' => $projects,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Project'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'year')->widget(Select2::classname(), [
        'data' => $years,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Year'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <!--?= $form->field($model, 'end_user_id')->textInput() ?-->

    <?= $form->field($model, 'head_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<pre>
    <?php print_r($years); ?>
    
    <?php print_r(Yii::$app->user->identity->profile); ?>
</pre>
<script>
$(document).ready(function(){
    $("#ppmp-project_id").hide();
    
});
</script>