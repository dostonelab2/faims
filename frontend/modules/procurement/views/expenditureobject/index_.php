<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\ExpenditureobjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expenditureobjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenditureobject-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Expenditureobject', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'expenditure_object_id',
            'expenditure_sub_class_id',
            'name',
            'object_code',
            'account_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
