<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\employeecompensation\Payrollitem */

$this->title = $model->payroll_item_id;
$this->params['breadcrumbs'][] = ['label' => 'Payrollitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payrollitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->payroll_item_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->payroll_item_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payroll_item_id',
            'payroll_id',
            'creditor_id',
            'salary',
            'gross_amount_earned',
        ],
    ]) ?>

</div>
