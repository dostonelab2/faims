<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestattachment */

$this->title = 'Update Requestattachment: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Requestattachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->request_attachment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="requestattachment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
