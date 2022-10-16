<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use kartik\select2\Select2

/* @var $this yii\web\View */
/* @var $model common\models\budget\Obligation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obligation-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--?= $form->field($model, 'financial_request_id')->textInput() ?-->

    <!--?= $form->field($model, 'obligation_number')->textInput(['maxlength' => true]) ?-->
    <?php echo '<label class="control-label">Obligration Requests</label>';
        echo Select2::widget([
            'name' => 'obligation_request_id',
            'data' => $listObligationrequests,
            'options' => [
                'placeholder' => 'Select Obligation Requests',
                //'multiple' => true
            ],
        ]); 
    ?>
    <br>
    
    <div id="obligation-request-details">
        <?php
            /*$query = Budgetallocationitem::find()->where(['category_id' => $model->expenditure_object_id  ]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => false,
            ]);*/

            //return Yii::$app->controller->renderPartial('_obligation_request_details', ['dataProvider'=>$dataProvider]);
            echo Yii::$app->controller->renderPartial('_obligation_request_details');
        ?>
    </div>
   
   <br>
    <!--?= $form->field($model, 'obligation_date')->textInput() ?-->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
