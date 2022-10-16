<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Requesttypeattachment */

$this->title = 'Update Requesttypeattachment: ' . $model->request_type_attachment_id;
$this->params['breadcrumbs'][] = ['label' => 'Requesttypeattachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->request_type_attachment_id, 'url' => ['view', 'id' => $model->request_type_attachment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="requesttypeattachment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
