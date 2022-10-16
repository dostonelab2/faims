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

use common\models\cashier\Creditor;
use common\models\finance\Requesttype;
use common\models\system\Profile;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequesttypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requesttype';
$this->params['breadcrumbs'][] = $this->title;

// Modal Create Requesttype
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalRequesttype',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

// Modal View Requesttype
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalViewRequesttype',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--?= Html::a('Create', ['create'], ['class' => 'btn btn-success', 'id' => 'buttonCreateRequesttype']) ?-->
    </p>
<?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'requesttype',
            'dataProvider' => $dataProvider,
            'columns' => [
                            [
                                'class' => 'kartik\grid\SerialColumn',
                                'contentOptions' => ['class' => 'kartik-sheet-style'],
                                'width' => '20px',
                                'header' => '',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                //'mergeHeader' => true,
                            ],
                            [
                                'attribute'=>'name',
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'100px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                },
                            ],
                            [
                                'attribute'=>'default_text',
                                'width'=>'500px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->default_text;
                                },
                            ],
                            [
                                'attribute'=>'default_text',
                                'header' => 'Required Documents',
                                'width'=>'500px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    $text = '';
                                    foreach($model->requesttypeattachments as $rqa){
                                        $text .= ' '.'<span class="label label-primary">'.$rqa->attachment->name.'</span>';
                                    }
                                    return $text;
                                },
                            ],
                            [
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
                                            'value' => Url::to(['requesttype/view?id='.$model->request_type_id]),
                                            //'value' => '/finance/requesttype/view?id=' . $model->request_type_id,
                                            //'onclick'=>'alert("asdasd")', 
                                            'id'=>'buttonViewRequesttype', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Requesttype")]);
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
                    'before'=>Html::button('New Requesttype', ['value' => Url::to(['request/create']), 'title' => 'Requesttype', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateRequesttype']),
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