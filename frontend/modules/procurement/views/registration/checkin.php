<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'College of Public Administration and Developmental Studies';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

    
    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['enablePushState' => false]); ?>
    
        <div style="height: 600px; width: 1200px; margin: 0 auto;">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                            'attribute'=>'name', 
                            'format'=>'raw', 
                            'value'=>function ($data) {
                                $btn = $data->checked_in ? 'btn-success' : 'btn-warning';
                                return Html::a($data->name, ['registration/checkin', 'id' => $data->id], ['class'=>'btn-sm '.$btn]);
                            },
                            //'value'=>"Html::a('Draw', ['registration/checkin'], ['class'=>'btn-sm btn-success', 'id'=> 'draw'])",
                            //'valueColOptions'=>['style'=>'width:25%']
                    ],
                    
                    [
                            'attribute'=>'gender', 
                            'format'=>'raw', 
                            'value'=>function ($data) {
                                //$btn = $data->gender ? 'btn-info' : 'btn-dark';
                                if($data->gender == 1){
                                    //$gender = 'Male';
                                    $btn1 = 'btn-info';
                                    $btn2 = 'btn-dark';
                                }elseif($data->gender == 2){
                                    //$gender = 'Female';
                                    $btn1 = 'btn-dark';
                                    $btn2 = 'btn-info';
                                }else{
                                    $btn1 = 'btn-dark';
                                    $btn2 = 'btn-dark';
                                }
                                    
                                
                                return Html::a('Male', ['registration/gender', 'id' => $data->id, 'gender' => 1], ['class'=>'btn-sm '.$btn1]).' '.
                                       Html::a('Female', ['registration/gender', 'id' => $data->id, 'gender' => 2], ['class'=>'btn-sm '.$btn2]);
                                
                            },
                    ],
//                [
//                    'class' => 'yii\grid\ActionColumn',
//                    'template' => '{checkin}',
//                    'contentOptions' => ['style' => 'text-align: center'], // For TD
//                    'buttons' => [
//                        'checkin' => function($url, $model) {
//                            return Html::a('<b class="fa fa-check-circle-o fa-2x "></b></span>', ['index', 'id' => $model['id']], ['title' => 'Check In', 'id' => 'modal-btn-view']);
//                        },
//                    ]
//                ],
                ],
            ]); ?>

        </div>
    <?php Pjax::end(); ?>
    
    
    

</div>
<script>
$('body').addClass('sidebar-collapse');
$('.main-header').hide();
$('footer.main-footer').hide();
</script>
