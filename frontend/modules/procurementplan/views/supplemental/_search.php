<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\SupplementalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppmpitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['additem', 'id' => 1, 'year' => 2020],
        'method' => 'get',
    ]); ?>

    <?=$form->field($ppmpitem, 'item_id')->dropDownList(
            ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'],['prompt' => 'Select Month...', 'onchange' => 'this.form.submit()']
    )->label(false);?>
    

    <?php ActiveForm::end(); ?>

</div>
