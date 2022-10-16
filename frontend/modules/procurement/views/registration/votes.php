<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\highcharts\HighCharts;
//use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'College of Public Administration and Developmental Studies';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

    
    <br/><br/><br/><br/><br/><br/><br/><br/>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
        <div style="height: 100px; width: 700px; margin: 0 auto;">
           
            <div id="barchart" style="min-width: 700px; height: 200px; max-width: 200px;">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                    'class',
                    //'entry',
                    [
                            'attribute'=>'entry', 
                            'format'=>'raw', 
                            'value'=>function ($data) {
                                return $data->entry;
                            },
                            'contentOptions' => ['style' => 'text-align: left; font-size: 1.8em;'], 
                    ], 
                    //'votes',
                    [
                            'attribute'=>'votes', 
                            'format'=>'raw', 
                            'value'=>function ($data) {
                                return number_format($data->votes);
                            },
                            'contentOptions' => ['style' => 'text-align: right; font-weight: bold; font-size: 1.8em; padding-right: 20px;'], 
                    ], 

                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>            
            
            </div>
        
        </div>
    
    
</div>
<script>
$('body').addClass('sidebar-collapse');
$('.main-header').hide();
$('footer.main-footer').hide();
</script>
