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

    
    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
        <div style="height: 100px; width: 1200px; margin: 0 auto;">
           
            <div id="barchart" style="min-width: 400px; height: 200px; max-width: 200px; float: left;">
                <?php echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'type' => 'bar'
                        ],
                        'title' => [
                             'text' => 'Participants by Gender'
                             ],
                        'xAxis' => [
                            'categories' => [
                                'Male',
                                'Female',
                            ]
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => 'Participants'
                            ]
                        ],
                        'series' => [
                            ['name' => 'Gender', 'data' => [count($maleCheckin), count($femaleCheckin)]],
                            //['name' => 'John', 'data' => [5, 7, 3]]
                        ]
                    ]
                ]);  ?>
            </div>
            
            <div id="barchart" style="min-width: 400px; height: 200px; max-width: 200px; float: left;">
                <?php echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'type' => 'pie'
                        ],
                        'title' => [
                             'text' => 'Attendees'
                             ],
                        'slice' => [
                            'categories' => [
                                'Present',
                                'Absent',
                            ]
                        ],
//                        'yAxis' => [
//                            'title' => [
//                                'text' => 'Participants'
//                            ]
//                        ],
                        'series' => [
                            ['name' => 'Attendees', 'data' => [count($present), count($absent)]],
                            //['name' => 'John', 'data' => [5, 7, 3]]
                        ]
                    ]
                ]);  ?>
            </div>
        
        </div>
    
    
</div>
<script>
$('body').addClass('sidebar-collapse');
$('.main-header').hide();
$('footer.main-footer').hide();
</script>
