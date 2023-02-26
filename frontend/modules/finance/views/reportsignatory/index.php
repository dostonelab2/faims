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

use common\models\procurement\Division;
use common\models\system\Appsettings;
use common\models\system\Profile;
use common\models\system\Usersection;
use common\models\sec\Blockchain;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report Signatories';
$this->params['breadcrumbs'][] = $this->title;

// Modal Add Setting
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalAddSignatory',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

// Modal Update Setting
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalUpdateSignatory',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

<?php //echo Yii::$app->controller->module->id;?>
<div class="reportsignatory-index">
<?php Pjax::begin(); ?>
     
      <?php
        echo GridView::widget([
            'id' => 'settings',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'columns' => [  
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute'=>'scope',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'20%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) {
                                    if($model->scope == 'OS')
                                        return 'Obligation Request and Status';
                                    elseif($model->scope == 'DV')
                                        return 'Disbursement Voucher';
                                },
                            ],
                            [
                                'attribute'=>'box',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'10%',
                                'format'=>'raw',
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute'=>'division_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: left; padding-left:'],
                                'width'=>'25%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) {
                                    return $model->division_id ? Division::findOne($model->division_id)->name : "";
                                },
                                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index.$model->division_id],
                                        'placement'=>'left',
                                        'name'=>'district',
                                        'asPopover' => true,
                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                        'data'=>  ArrayHelper::map(Division::find()->asArray()->all(), 'division_id', 'name'),
                                        'formOptions'=>['action' => ['/finance/reportsignatory/assign']], // point to the new action
                                    ];
                                },
                                'hAlign' => 'right', 
                                'vAlign' => 'middle',
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute'=>'active_user',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'25%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) {
                                    return $model->active_user ? Profile::findOne($model->active_user)->fullname : "";
                                },
                                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index.$model->active_user],
                                        'placement'=>'left',
                                        'name'=>'district',
                                        'asPopover' => true,
                                        // 'disabled'=> ($model->index_type === "text") ? false : true,
                                        'inputType' => Editable::INPUT_TEXT,
                                        'formOptions'=>['action' => ['/finance/reportsignatory/assign']], // point to the new action
                                    ];
                                },
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute'=>'oic_position',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'25%',
                                'format'=>'raw',
                                /*'value'=>function ($model, $key, $index, $widget) {
                                    return $model->active_user ? Profile::findOne($model->active_user)->fullname : "";
                                },*/
                                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index.$model->oic_position],
                                        'placement'=>'left',
                                        'name'=>'oic_position',
                                        'asPopover' => true,
                                        // 'disabled'=> ($model->index_type === "text") ? false : true,
                                        'inputType' => Editable::INPUT_TEXT,
                                        'formOptions'=>['action' => ['/finance/reportsignatory/assign']], // point to the new action
                                    ];
                                },
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                            ],
                            [
                                'attribute'=>'report_signatory_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return '<span class="label label-info">'.($model->status ? $model->status->name : "").'</span>';
                                    return Html::button('<i class="glyphicon glyphicon-pencil"></i> ', ['value' => Url::to(['reportsignatory/update', 'id'=>$model->report_signatory_id]), 'title' => Yii::t('app', "Update"), 'class' => 'btn btn-md btn-info', 'id'=>'buttonUpdateSignatory']);
                                },
                            ],
                    ],
            
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => Html::encode($this->title),
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>  Html::button('New Setting', ['value' => Url::to(['appsettings/create']), 'title' => 'Add Setting', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddSetting']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=> '',
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