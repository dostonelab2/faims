<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable; 
use kartik\grid\GridView;
use yii\bootstrap\Modal;

use common\models\cashier\Creditortype;
use common\models\system\Profile;
/* @var $this yii\web\View */
/* @var $searchModel common\models\cashier\CreditortmpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'New Payee / Creditor Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creditortmp-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php
        echo GridView::widget([
            'id' => 'creditortmp',
            'dataProvider' => $dataProvider,
            'columns' => [
                            [
                                'attribute'=>'name',
                                'headerOptions' => ['style' => 'text-align: center; padding-left: 10px;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: left; font-weight: bold;'],
                                'width'=>'30%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                }
                            ],
                            [
                                'attribute'=>'address',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: left;'],
                                'width'=>'30%',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->address;
                                },
                            ],
                            [
                                'attribute'=>'tin_number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align: middle; text-align: right; padding-right: 20px; font-weight: bold;'],
                                'width'=>'10%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->tin_number;
                                },
                            ],
                            [
                                'class'=>'kartik\grid\EditableColumn',
                                'attribute'=>'creditor_type_id',
                                'header'=>'Creditor Type',
                                //'width'=>'350px',
                                'refreshGrid'=>true,
                                //'readonly' => !$isMember,
                                'value'=>function ($model, $key, $index, $widget) {
                                        if($model->creditor_type_id)
                                            return $model->type->name;
                                        else
                                            return '-';
                                    },
                                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index . '_10_' . $model->creditor_type_id],
                                        'contentOptions' => ['style' => 'text-align: center;  vertical-align:middle;'],
                                        'placement'=>'left',
                                        'name'=>'district',
                                        'asPopover' => true,
                                        'value'=>function ($model, $key, $index, $widget) {
                                            if($model->creditor_type_id)
                                                return $model->type->name;
                                            else
                                                return '-';
                                        },
                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                        'data'=>ArrayHelper::map(Creditortype::find()->all(),'creditor_type_id','name'),
                                        'formOptions'=>['action' => ['/cashier/creditortmp/updatecreditor']], // point to the new action
                                    ];
                                },
                                'headerOptions' => ['style' => 'text-align: center'],
                                'contentOptions' => ['style' => 'text-align: left; vertical-align:middle;'],
                                'hAlign'=>'right',
                                //'vAlign'=>'middle',
                                'width'=>'250px',
                            ],
                            [
                                'attribute'=>'requested_by',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle; '],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return Profile::findOne($model->requested_by)->fullname;
                                    return $model->profile->fullname;
                                },
                            ],
                            [
                                'class'=>'kartik\grid\EditableColumn',
                                'attribute'=>'active',
                                'header'=>'Approve',
                                //'width'=>'350px',
                                'refreshGrid'=>true,
                                'readonly' => function ($model, $key, $index, $widget) {
                                        if($model->active)
                                            return true;
                                        else
                                            return false;
                                    },
                                'value'=>function ($model, $key, $index, $widget) {
                                        if($model->active)
                                            return 'YES';
                                        else
                                            return 'NO';
                                    },
                                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index . '_20_' . $model->active],
                                        'contentOptions' => ['style' => 'text-align: center;'],
                                        'placement'=>'left',
                                        'name'=>'active',
                                        'asPopover' => true,
                                        'value'=>function ($model, $key, $index, $widget) {
                                            if($model->active)
                                                return 'YES';
                                            else
                                                return 'NO';
                                        },
                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                        'data'=>['0'=>'NO', '1'=>'YES'], // any list of values
                                        'formOptions'=>['action' => ['/cashier/creditortmp/updatecreditor']], // point to the new action
                                    ];
                                },
                                'headerOptions' => ['style' => 'text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center'],
                                'hAlign'=>'right',
                                //'vAlign'=>'middle',
                                'width'=>'250px',
                            ],
                            /*[
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/obligationtype/view?id=' . $model->creditor_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Obligation Type")]);
                                    },
                                ],
                            ],*/
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>'',/*Html::button('New Obligation Type', ['value' => Url::to(['cr/create']), 'title' => 'Request', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateObligationtype']),*/
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
</div>
