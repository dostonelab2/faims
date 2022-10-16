<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\apiservice\Notification */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->notification_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->notification_id], [
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
            'notification_id',
            'hash',
            'sender',
            'recipient',
            'status',
            'title',
            'message:ntext',
            'via',
            'created_at',
            'module',
            'action',
        ],
    ]) ?>

</div>
