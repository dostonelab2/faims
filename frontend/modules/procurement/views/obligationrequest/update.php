<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */

$this->title = 'Update Obligationrequest: ' . $model->obligation_request_id;
$this->params['breadcrumbs'][] = ['label' => 'Obligationrequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->obligation_request_id, 'url' => ['view', 'id' => $model->obligation_request_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="obligationrequest-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'assig' => $assig,
    ]) ?>

</div>
