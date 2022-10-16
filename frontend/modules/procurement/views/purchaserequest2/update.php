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
    <?= $this->render('_form', [
        'model' => $model,
        'itemDataProvider' => $itemDataProvider,
        'itemDataProvider2' => $itemDataProvider2
    ]) ?>

</div>
