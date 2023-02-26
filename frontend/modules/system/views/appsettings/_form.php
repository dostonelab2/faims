<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;
use kartik\editable\Editable; ;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

use common\models\procurement\Division;
use common\models\system\Appsettings;
use common\models\system\Profile;

/* @var $this yii\web\View */
/* @var $model common\models\system\Appsettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appsettings-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <!--?= $form->field($model, 'module_id')->textInput(['maxlength' => true]) ?-->
        <div class="col-md-6">
            <?= $form->field($model, 'module_id')->widget(Select2::classname(), [
                //'data' => ArrayHelper::map(Obligationtype::find()->all(),'type_id','name'),
                'data' => [
                    "agency" => "agency", 
                    "budget" => "budget", 
                    "cashier" => "cashier", 
                    "finance" => "finance", 
                    "procurement" => "procurement", 
                    "system" => "system",  
                ],
                'language' => 'en',
                // 'theme' => Select2::THEME_DEFAULT,
                'options' => ['placeholder' => 'Select Module'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Module'); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'index_type')->widget(Select2::classname(), [
                //'data' => ArrayHelper::map(Obligationtype::find()->all(),'type_id','name'),
                'data' => [
                    "user" => "user", 
                    "text" => "text", 
                ],
                'language' => 'en',
                // 'theme' => Select2::THEME_DEFAULT,
                'options' => ['placeholder' => 'Select Module'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Module'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"> 
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"> 
        <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Division::find()->all(),'division_id','name'),
                'language' => 'en',
                // 'theme' => Select2::THEME_DEFAULT,
                'options' => ['placeholder' => 'Select Division'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Division'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12"> 
            <?= $form->field($model, 'index_value')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"> 
        <?= $form->field($model, 'index_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Profile::find()->orderBy(['firstname' => SORT_ASC])->asArray()->all(), 'profile_id', 
                    function($model) {
                        return $model['firstname'].' '.$model['lastname'];
                    }),
                'language' => 'en',
                // 'theme' => Select2::THEME_DEFAULT,
                'options' => ['placeholder' => 'Select Division'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('User ID'); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
