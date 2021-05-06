<?php

use common\models\evaluation\Feedback;
use kartik\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
?>
<?php
//$totalresponse = $feedback->select(['count(*) as totalresponse'])->where(['month(feedback_date)' => $_GET['month'],'year(feedback_date)' => $_GET['year'],'business_unit_id' => $_GET['id']])->asArray()->one();
?>
<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <div class="btn-group pull-right">
        <a class="btn-print" href=#><i class="fa fa-print"></i></a>
        </div>
    </div>
    <div class="panel-body">
        <!--h4><b  style="color:orangered">Total No. of Respondents:</b> <b><?php//echo $totalresponse['totalresponse'];?></b></h4-->
        <h4 style="background-color:gainsboro"><b>PART I: CUSTOMER RATING OF SERVICE QUALITY</b></h4>
            <?php
            echo GridView::widget([
                'dataProvider' => $evaluatioAttribProvider,
                'summary' => '',
                'showPageSummary' => true,
                'columns' => [
                    //'evaluation_attribute_id',
                    //'business_unit_id',
                    [
                        'attribute' => 'attribute_name',
                        'header' => 'SERVICE QUALITY ITEMS'
                    ],
                    [
                        'attribute' => 'rating5',
                        'header' => 'Very Satisfied',
                    ],
                    [
                        'attribute' => 'score5',
                        'header' => '5',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'rating4',
                        'header' => 'Quite Satisfied'
                    ],
                    [
                        'attribute' => 'score4',
                        'header' => '4',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'rating3',
                        'header' => 'Neutral'
                    ],
                    [
                        'attribute' => 'score3',
                        'header' => '3',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'rating2',
                        'header' => 'Quite disatisfied'
                    ],
                    [
                        'attribute' => 'score2',
                        'header' => '2',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'rating1',
                        'header' => 'Very disatisfied',
                    ],
                    [
                        'attribute' => 'score1',
                        'header' => '1',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'deliveryscoretotal',
                        'header' => 'TOTAL SCORE'
                    ],
                    [
                        'attribute' => 'ss',
                        'header' => 'SS',
                        'format' => ['decimal', 2],
                        //'pageSummary' => true
                        //'value' => 'deliveryscoretotal'
                    ],
                    [
                        'attribute' => 'gap',
                        'header' => 'GAP',
                        'format' => ['decimal', 2],
                        //'pageSummary' => true
                        //'value' => 'deliveryscoretotal'
                    ],

                ],
                'responsive' => true,
                'hover' => true
            ]);
            ?>
            <h4 style="background-color:gainsboro"><b>PART II: IMPORTANCE OF THESE ATTRIBUTES TO THE CUSTOMERS</b></h3>
                <?php
                echo GridView::widget([
                    'dataProvider' => $importanceAttribbProvider,
                    'summary' => '',
                    'showPageSummary' => true,
                    'columns' => [
                        //'evaluation_attribute_id',
                        //'business_unit_id',
                        [
                            'attribute' => 'attribute_name',
                            'header' => 'IMPORTANCE OF SERVICE QUALITY ATTRIBUTES TO CUSTOMERS'
                        ],
                        [
                            'attribute' => 'rating5', //'rating5',
                            'header' => 'Very Satisfied'
                        ],
                        [
                            'attribute' => 'score5', //'rating5',
                            'header' => '5'
                        ],
                        [
                            'attribute' => 'rating4',
                            'header' => 'Quite Satisfied'
                        ],
                        [
                            'attribute' => 'score4', //'rating5',
                            'header' => '4'
                        ],
                        [
                            'attribute' => 'rating3',
                            'header' => 'Neutral'
                        ],
                        [
                            'attribute' => 'score3', //'rating5',
                            'header' => '3'
                        ],
                        [
                            'attribute' => 'rating2',
                            'header' => 'Quite disatisfied'
                        ],
                        [
                            'attribute' => 'score2', //'rating5',
                            'header' => '2'
                        ],
                        [
                            'attribute' => 'rating1',
                            'header' => 'Very disatisfied'
                        ],
                        [
                            'attribute' => 'score1', //'rating5',
                            'header' => '1'
                        ],
                        [
                            'attribute' => 'importancetotalscore', //'rating5',
                            'header' => 'TOTAL SCORE'
                        ],
                        [
                            'attribute' => 'is',
                            'header' => 'IS',
                            'format' => ['decimal', 2],
                            'pageSummary' => true
                            //'value' => 'deliveryscoretotal'
                        ],
                    ],
                    'responsive' => true,
                    'hover' => true
                ]);
            ?>
   </div>
</div>

<style>
.btn-print {
  background-color: #337ab7;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 20px;
  width: 100px;
  height: 100px;
}

/* Darker background on mouse-over */
.btn-print:hover {
  background-color: #22547a;
}
</style>