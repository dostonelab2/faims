<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Creditortmp */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Creditortmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creditortmp-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->creditor_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->creditor_id], [
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
            'creditor_id',
            'creditor_type_id',
            'name',
            'address',
            'bank_name',
            'account_number',
            'tin_number',
            'requested_by',
            'date_request',
            'active',
        ],
    ]) ?>

</div>
