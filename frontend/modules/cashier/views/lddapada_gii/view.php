<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapada */

$this->title = $model->lddapada_id;
$this->params['breadcrumbs'][] = ['label' => 'Lddapadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lddapada-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->lddapada_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->lddapada_id], [
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
            'lddapada_id',
            'batch_number',
            'batch_date',
            'certified_correct_id',
            'approved_id',
            'validated1_id',
            'validated2_id',
            'created_by',
        ],
    ]) ?>

</div>
