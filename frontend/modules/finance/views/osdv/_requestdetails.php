<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

?>
           

    <?php $attributes = [
            [
                'attribute'=>'request_type_id',
                'label'=>'Request Type',
                'inputContainer' => ['class'=>'col-sm-6'],
                'format'=>'raw',
                'value' => $model->requesttype->name,
            ],
            [
                'attribute'=>'payee_id',
                'label'=>'Payee',
                'inputContainer' => ['class'=>'col-sm-6'],
                'format'=>'raw',
                'value' => $model->creditor->name,
//                'type'=>DetailView::INPUT_SELECT2, 
//                'widgetOptions'=>[
//                    'data'=>ArrayHelper::map(Creditor::find()->orderBy(['name'=>SORT_ASC])->all(),'creditor_id','name'),
//                    'options' => ['placeholder' => 'Select Payee'],
//                    'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
//                ],
            ],
            [
                'attribute'=>'particulars',
                'label'=>'Particulars',
                'inputContainer' => ['class'=>'col-sm-6'],
            ],

            [
                'attribute'=>'amount',
                'label'=>'Amount (P)',
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'attribute'=>'payroll',
                'id'=>'payrollVar',
                'label'=>'Payroll',
                'value' => $model->payroll ? 'YES' : 'NO',
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
        ];?>
    <?= DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            'container' => ['id'=>'kv-demo'],
            'buttons1' => '',
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                'heading'=>'REQUEST Details',
                'type'=>DetailView::TYPE_PRIMARY,
            ],
        ]); ?>