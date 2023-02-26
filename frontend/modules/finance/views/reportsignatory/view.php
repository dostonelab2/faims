<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Reportsignatory */

$this->title = $model->report_signatory_id;
$this->params['breadcrumbs'][] = ['label' => 'Reportsignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reportsignatory-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->report_signatory_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->report_signatory_id], [
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
            'report_signatory_id',
            'division_id',
            'scope',
            'box',
            'user1',
            'user2',
            'user3',
            'active_user',
        ],
    ]) ?>

</div>
