<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\procurementplan\Ppmp;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\PpmpitemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="ppmpitem-search">


        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <?= $form->field($model, 'selectyear')->dropDownList(
            ArrayHelper::map(Ppmp::find()->all(), 'year', 'year'),
            [
                'class' => 'form-control',
                'prompt' => 'Select Year...',
                //'onchange' => 'selectMonth(this.value)',
                //'id' => 'dropdown',
                'onchange' => 'this.form.submit()',
                'style' => 'width:250px; font-weight:bold;',
                'id' => 'cboYear'
            ]
        )->label(false); ?>


        <?php ActiveForm::end(); ?>

    </div>
