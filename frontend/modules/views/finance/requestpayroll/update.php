<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestpayroll */

$this->title = 'Update Requestpayroll: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Requestpayrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->request_payroll_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="requestpayroll-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
