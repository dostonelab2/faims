<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequesttypeattachmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requesttypeattachments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requesttypeattachment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Requesttypeattachment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'request_type_attachment_id',
            'request_type_id',
            'attachment_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
