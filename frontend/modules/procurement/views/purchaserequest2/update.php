<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Purchaserequest */

$this->title = 'Update Purchaserequest: ' . $model->purchase_request_id;
$this->params['breadcrumbs'][] = ['label' => 'Purchaserequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->purchase_request_id, 'url' => ['view', 'id' => $model->purchase_request_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchaserequest-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
