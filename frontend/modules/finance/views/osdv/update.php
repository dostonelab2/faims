<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Osdv */

$this->title = 'Update Osdv: ' . $model->osdv_id;
$this->params['breadcrumbs'][] = ['label' => 'Osdvs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->osdv_id, 'url' => ['view', 'id' => $model->osdv_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="osdv-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
