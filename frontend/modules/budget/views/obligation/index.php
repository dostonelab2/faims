<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\cashier\Obligation;

/* @var $this yii\web\View */
/* @var $searchModel common\models\budget\ObligationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Obligations';
$this->params['breadcrumbs'][] = $this->title;

// Modal Create Obligation
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalObligation',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>
<div class="obligation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--?= Html::a('Create Obligation', ['create'], ['class' => 'btn btn-success']) ?-->
    </p>
    <?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'obligation',
            'dataProvider' => $dataProvider,
            'columns' => [
                            [
                                'attribute'=>'obligation_number',
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->obligation_number;
                                },
                            ],
                            [
                                'attribute'=>'obligation_date',
                                'contentOptions' => ['style' => 'padding-left: 25px;'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->obligation_date;
                                },
                            ],
                            [
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/budget/obligation/view?id=' . $model->obligation_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Obligation")]);
                                    },
                                ],
                            ],
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('New Obligation', ['value' => Url::to(['obligation/create']), 'title' => 'OBLIGATION', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateObligation']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>'',
                                    /*Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn-primary', 'style'=>'width: 90px; margin-right: 6px;']) .
                                    Html::button('APPROVED', ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;'])*/
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
    

        ?>
        <?php Pjax::end(); ?>
</div>
