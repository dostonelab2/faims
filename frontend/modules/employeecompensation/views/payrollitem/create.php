<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\employeecompensation\Payrollitem */

$this->title = 'Create Payrollitem';
$this->params['breadcrumbs'][] = ['label' => 'Payrollitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payrollitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
