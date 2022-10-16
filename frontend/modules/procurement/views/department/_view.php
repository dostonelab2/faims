<?php
/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/16/2018
 * Time: 9:16 AM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Department */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="department-formview">

    <?php $form = ActiveForm::begin();

    ?>
    <div class="form-group">
        <div class="row-fluid">
            <div class="col-lg-12-block">
                <div class="col-lg-4-block">
                    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true,'required'=>true,'readonly'=>true]) ?>
                </div>
            </div>
            <div class="col-lg-12-block">
                <div class="col-lg-4-block">
                    <?= $form->field($model, 'department_desc')->textInput(['maxlength' => true,'required'=>true,'readonly'=>true]) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>



</div>
