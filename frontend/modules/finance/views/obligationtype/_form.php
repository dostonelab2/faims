<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use common\models\finance\Fundcategory;
use common\models\procurement\Project;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Obligationtype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obligationtype-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'fund_category_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Fundcategory::find()->all(),'fund_category_id','name'),
                    'language' => 'en',
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Fund Category'); ?>
                
    <?= $form->field($model, 'project_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Project::find()->all(),'project_id','code'),
                    'language' => 'en',
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    'pluginEvents'=>[
                        "change" => 'function() { 
                            $("#obligationtype-name").val($("#select2-obligationtype-project_id-container").attr("title"));
                        }
                    ']
                ])->label('Project'); ?>
                
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
