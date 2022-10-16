<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapadaitem */

$this->title = 'Update Lddapadaitem: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lddapadaitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->lddapada_item_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lddapadaitem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
