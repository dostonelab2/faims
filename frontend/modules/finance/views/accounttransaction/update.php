<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Accounttransaction */

$this->title = 'Update Accounttransaction: ' . $model->account_transaction_id;
$this->params['breadcrumbs'][] = ['label' => 'Accounttransactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->account_transaction_id, 'url' => ['view', 'id' => $model->account_transaction_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="accounttransaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
