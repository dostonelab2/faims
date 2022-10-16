<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\employeecompensation\Payroll */

$this->title = 'Create Payroll';
$this->params['breadcrumbs'][] = ['label' => 'Payrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
