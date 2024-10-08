<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\system\AppsettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appsettings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appsettings-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Appsettings', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'setting_id',
            'module_id',
            'name',
            'index_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
