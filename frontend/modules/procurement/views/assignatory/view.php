<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Assignatory */

$this->title = $model->assignatory_id;
$this->params['breadcrumbs'][] = ['label' => 'Assignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignatory-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->assignatory_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->assignatory_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'assignatory_id',
            'CompanyTitle',
            'RegionOffice',
            'Address',
            'report_title',
            [
                'attribute'=>'assignatory_1',
                'label'=>'Assignatory 1',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_1=='') {
                        return '';
                    }else{
                        return $data->assig1->firstname . ' ' . $data->assig1->middleinitial .'. '. $data->assig1->lastname;  
                    }
                
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'attribute'=>'assignatory_2',
                'label'=>'Assignatory 2',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_2=='') {
                        return '';
                    }else{
                    return $data->assig2->firstname . ' ' . $data->assig2->middleinitial .'. '. $data->assig2->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'attribute'=>'assignatory_3',
                'label'=>'Assignatory 3',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_3=='') {
                        return '';
                    }else{
                    return $data->assig3->firstname . ' ' . $data->assig3->middleinitial .'. '. $data->assig3->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute'=>'assignatory_4',
                'label'=>'Assignatory 4',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_4=='') {
                        return '';
                    }else{
                    return $data->assig4->firstname . ' ' . $data->assig4->middleinitial .'. '. $data->assig4->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute'=>'assignatory_5',
                'label'=>'Assignatory 5',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_5=='') {
                        return '';
                    }else{
                    return $data->assig5->firstname . ' ' . $data->assig5->middleinitial .'. '. $data->assig5->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute'=>'assignatory_6',
                'label'=>'Assignatory 6',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_6=='') {
                        return '';
                    }else{
                    return $data->assig6->firstname . ' ' . $data->assig6->middleinitial .'. '. $data->assig6->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
        ],
    ]) ?>

</div>
