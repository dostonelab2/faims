<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Expenditure */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expenditures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenditure-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->expenditure_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->expenditure_id], [
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
            'expenditure_id',
            'expenditure_class_id',
            'expenditure_subclass_id',
            'expenditure_object_id',
            'year',
            'name',
            'code',
            'amount',
        ],
    ]) ?>

</div>
