<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestpayrollitem */

$this->title = 'Update Requestpayrollitem: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Requestpayrollitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->request_payroll_item_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="requestpayrollitem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
