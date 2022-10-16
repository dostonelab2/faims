<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapada */

$this->title = 'Update Lddapada: ' . $model->lddapada_id;
$this->params['breadcrumbs'][] = ['label' => 'Lddapadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lddapada_id, 'url' => ['view', 'id' => $model->lddapada_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lddapada-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
