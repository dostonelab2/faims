<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapadaitem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lddapadaitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lddapadaitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->lddapada_item_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->lddapada_item_id], [
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
            'lddapada_item_id',
            'lddapada_id',
            'creditor_id',
            'type',
            'name',
            'bank_name',
            'account_number',
            'gross_amount',
            'alobs_id',
            'expenditure_object_id',
            'check_number',
        ],
    ]) ?>

</div>
