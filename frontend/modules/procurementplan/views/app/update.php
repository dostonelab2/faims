<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */

$this->title = 'Update Ppmpitem: ' . $model->ppmp_item_id;
$this->params['breadcrumbs'][] = ['label' => 'Ppmpitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ppmp_item_id, 'url' => ['view', 'id' => $model->ppmp_item_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ppmpitem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
