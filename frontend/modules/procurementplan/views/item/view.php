<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Item */

$this->title = strlen($model->item_name) > 50 ? substr($model->item_name,0,50) . '...' : $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->item_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->item_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
    $fmt = Yii::$app->formatter;
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_id',
            //'item_category_id',
            [
                'attribute' => 'category_name',
                'value' => $model->itemcategory->category_name
            ],
            'item_code',
            'item_name',
            //'unit_of_measure_id',
            [
                'attribute' => 'unit_of_measure_id',
                'value' => $model->getUnit()
            ],
            [
                'attribute' => 'price_catalogue',
                'value' => $fmt->asDecimal($model->price_catalogue)
            ],
            
            //'last_update',
        ],
    ]) ?>

</div>
