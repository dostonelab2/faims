<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Unittype */

$this->title = $model->unit_type_id;
$this->params['breadcrumbs'][] = ['label' => 'Unittypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unittype-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->unit_type_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->unit_type_id], [
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
            'unit_type_id',
            'name_short',
            'name_long',
        ],
    ]) ?>

</div>
