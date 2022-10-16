<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Accounttransaction */

$this->title = $model->account_transaction_id;
$this->params['breadcrumbs'][] = ['label' => 'Accounttransactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounttransaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->account_transaction_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->account_transaction_id], [
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
            'account_transaction_id',
            'request_id',
            'account_id',
            'transaction_type',
            'amount',
        ],
    ]) ?>

</div>
