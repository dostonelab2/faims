<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Attachmenttype */

$this->title = 'Update Attachmenttype: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Attachmenttypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->attachment_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="attachmenttype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
