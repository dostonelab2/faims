<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Fundsource */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fundsources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fundsource-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->fund_source_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->fund_source_id], [
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
            'fund_source_id',
            'code',
            'name:ntext',
            'active',
        ],
    ]) ?>

</div>
