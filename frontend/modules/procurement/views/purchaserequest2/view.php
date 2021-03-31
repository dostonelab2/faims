<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Purchaserequest */

$this->title = $model->purchase_request_id;
$this->params['breadcrumbs'][] = ['label' => 'Purchaserequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchaserequest-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->purchase_request_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->purchase_request_id], [
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
            'purchase_request_id',
            'purchase_request_number',
            'purchase_request_sai_number',
            'division_id',
            'section_id',
            'purchase_request_date',
            'purchase_request_saidate',
            'purchase_request_purpose:ntext',
            'purchase_request_referrence_no',
            'purchase_request_project_name',
            'purchase_request_location_project',
            'purchase_request_requestedby_id',
            'purchase_request_approvedby_id',
            'user_id',
        ],
    ]) ?>

</div>
