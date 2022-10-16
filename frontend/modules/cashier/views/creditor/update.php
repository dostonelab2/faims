<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Creditor */

$this->title = 'Update Creditor: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Creditors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->creditor_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="creditor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
