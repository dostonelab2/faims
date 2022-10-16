<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;

/* @var $form yii\widgets\ActiveForm */
?>

<div class="submit-not-eligible">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'not-allowed'
                    ]
        ]);
    ?>


    <?php ActiveForm::end(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'id'=>'expenditure_objects', //additional
        'pjax' => true, // pjax is set to always true for this demo
                'pjaxSettings' => [
                        'options' => [
                            'enablePushState' => false,
                        ]
                    ],
        'columns' => [
            [
                    'class' => 'kartik\grid\SerialColumn',
                    //'contentOptions' => ['class' => 'kartik-sheet-style'],
                    //'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    //'mergeHeader' => true,
                ],
            [
                'attribute' => 'message',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->message;
                        },
            ],
    ]]); 
    ?>
    <div class="form-group">
        <center><?= Html::Button('Close', ['class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { $("#modalContainer").modal("hide");; })();']) ?></center>
    </div>
</div>