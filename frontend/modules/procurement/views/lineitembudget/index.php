<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView; 
use kartik\editable\Editable; 
use kartik\grid\EditableColumnAction;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\Employeesearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Line Item Budget';
$this->params['breadcrumbs'][] = $this->title;

// Modal LIB
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalLib',
    'size' => 'modal-md',
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();


?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    // the grid columns setup (only two column entries are shown here
    // you can add more column entries you need for your use case)
        $gridColumns = [
        // the name column configuration
            
        [
           'label'=>'title',
           'format' => 'raw',
           'value'=>function ($data) {
                return Html::a($data['title'],['view', 'id'=>$data['line_item_budget_id']]);
            },
        ],

        [
            'class' => '\kartik\grid\ActionColumn',
            'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
        ]

        ];

        echo \kartik\grid\GridView::widget([
            'dataProvider'=>$dataProvider,
            'pjax' => true,
            'panel' => [
                'heading' => '<b>Expenditure Object</b>',
                'type' => Gridview::TYPE_PRIMARY,
            ],
            'toolbar' => 
                        [
                            [
                                'content'=>
                                    Html::button('New Line-Item Budget', ['value' => Url::to(['lineitembudget/create']), 'title' => 'Expenditure Class', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddLibObject']) .
                                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => true, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                            ],
                            '{export}',
                            '{toggleData}'
                        ],
            'filterModel'=>$searchModel,
            'columns'=>$gridColumns
        ]);
    ?>
</div>
<pre>
    <?php //print_r(Yii::$app->user->identity->profile);?>
</pre>
