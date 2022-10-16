<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'College of Public Administration and Developmental Studies';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">
    
    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
       <br>
              
        
        
        <div style="width: 1200px; margin: 0 auto; text-align: center">
            <?= Html::a('Draw', ['registration/drawnames'], ['class'=>'btn-sm btn-success', 'id'=> 'draw']) ?>
            <?= Html::a('Clear', ['registration/clearnames'], ['class'=>'btn-sm btn-success', 'id'=> 'clear']) ?>
            
            <!--h3>Please proceed to the stage if your name is listed below:</h3-->
            <h3>The following are requested to come forward.</h3>
        </div>
        
        <br/>
        <br/>    
        <div style="height: 700; width: 1400px; margin: 0 auto;">
            
            
            <?php Pjax::begin(['enablePushState' => false]); ?>
                <?php
                if(count($winners)){
                    foreach($winners as $winner)
                    {
                        echo Html::a($winner->name, ['registration/replacename', 'id'=>$winner->id], ['class'=>'btn btn-warning winners', 'style'=> 'width: 300px; font-weight: bold; text-align: left; float: left; margin-left: 40px; margin-bottom: 10px;']);
                    }
                }
                ?>
                
            <?php Pjax::end(); ?>
        </div>
        
        <div class="loadpartial" id="loadpartial" style="float: left; position: absolute; width: 1400px; margin-top: -50px; margin: 0 auto;">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        
        
        
</div>
<script>
$('body').addClass('sidebar-collapse');
//$('.main-header').hide();
$('#loadpartial').hide();

//$('#draw, #clear').click(function(){
//    $('#loadpartial').show();
//    //delay(10000);
//    $('#loadpartial').hide();
//});
    

</script>



