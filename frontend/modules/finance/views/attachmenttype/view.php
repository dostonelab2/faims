<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Attachmenttype */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Attachmenttypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attachmenttype-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->attachment_type_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->attachment_type_id], [
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
            'attachment_type_id',
            'name',
            'active',
        ],
    ]) ?>

</div>
