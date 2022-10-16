<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\AttachmenttypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Attachmenttypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attachmenttype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Attachmenttype', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'attachment_type_id',
            'name',
            'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
