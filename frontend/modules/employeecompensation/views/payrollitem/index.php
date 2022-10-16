<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\employeecompensation\PayrollitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payrollitems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payrollitem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Payrollitem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'payroll_item_id',
            'payroll_id',
            'creditor_id',
            'salary',
            'gross_amount_earned',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
