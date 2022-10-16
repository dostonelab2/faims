<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestattachment */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Requestattachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requestattachment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->request_attachment_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->request_attachment_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'request_attachment_id',
            'request_id',
            'name',
            'attachment_type_id',
        ],
    ]) ?>

</div>
