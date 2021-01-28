<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\procurementplan\Itemcategory;
use common\models\procurementplan\Unitofmeasure;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_code')->textInput() ?>

    <?= $form->field($model, 'item_category_id')->dropDownList(
        ArrayHelper::map(Itemcategory::find()->where(['status' => 1])->all(),'item_category_id','category_name'),
        [
            'prompt' => 'Select Category...',
        ]
        ) ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit_of_measure_id')->dropDownList(
        ArrayHelper::map(Unitofmeasure::find()->where(['status' => 1])->all(),'unit_of_measure_id','name'),
        [
            'prompt' => 'Select Unit of Measurement...'
        ]
    ) ?>

    <?= $form->field($model, 'price_catalogue')->textInput() ?>

    <?= $form->field($model, 'availability')->dropDownList(
        [
            '1' => 'AVAILABLE AT PROCUREMENT SERVICE STORES',
            '2' => 'ITEMS NOT AVAILABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES'
        ],
        [
            'prompt' => 'Select Availability...'
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
