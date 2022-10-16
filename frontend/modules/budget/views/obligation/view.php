<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Obligation */

$this->title = $model->obligation_id;
$this->params['breadcrumbs'][] = ['label' => 'Obligations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obligation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->obligation_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->obligation_id], [
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
            'obligation_id',
            'financial_request_id',
            'obligation_number',
            'obligation_date',
        ],
    ]) ?>

</div>
