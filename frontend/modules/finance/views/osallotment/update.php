<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Osallotment */

$this->title = 'Update Osallotment: ' . $model->os_allotment_id;
$this->params['breadcrumbs'][] = ['label' => 'Osallotments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->os_allotment_id, 'url' => ['view', 'id' => $model->os_allotment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="osallotment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
