<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestattachmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requestattachments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requestattachment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Requestattachment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'request_attachment_id',
            'request_id',
            'name',
            'attachment_type_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
