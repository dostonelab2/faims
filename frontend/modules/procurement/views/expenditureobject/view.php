<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Expenditureobject */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expenditureobjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenditureobject-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->expenditure_object_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->expenditure_object_id], [
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
            'expenditure_object_id',
            'expenditure_sub_class_id',
            'name',
            'object_code',
            'account_code',
        ],
    ]) ?>

</div>
