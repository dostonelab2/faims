<?php
/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/16/2018
 * Time: 9:16 AM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\procurement\Expenditureobject;
use common\models\procurement\ExpenditureobjectSearch;
use common\models\procurement\Expenditureclass;
use common\models\procurement\Lineitembudgetobject;

    
/* @var $this yii\web\View */
/* @var $model common\models\procurement\Department */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="Lineitembudget-form">
  
   
    <?php $form = ActiveForm::begin(
        ['action' =>['lineitembudget/create']]
    );

    ?>
    
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'title')->textarea(['rows' => '3']) ?>
            <!--?= $form->field($model, 'title')->textInput() ?-->
        </div>
        
    </div>
    
    <?php $model->division_id = Yii::$app->user->identity->profile->division_id;?>
    <?php $model->section_id = Yii::$app->user->identity->profile->unit_id;?>
    
    <div class="row">
        <div class="col-lg-6">
        
        <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
                'data' => $listDivisions,
                'language' => 'en',
                'options' => ['placeholder' => 'Select Division'],
                //'disabled' => true,
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-lg-6">
        <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                        'data' => $listTypes,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select LIB Type'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'section_id')->widget(Select2::classname(), [
                        'data' => $listSections,

                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Section'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>            
        </div>
        <div class="col-lg-6">
        <?= $form->field($model, 'subtype_id')->widget(Select2::classname(), [
                        'data' => $listSubTypes,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select LIB Type'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'period')->textInput() ?>
        </div>
        
        <div class="col-lg-12-block">
                    <div class="col-lg-8-block">
                    <h1></h1>
                    <div id="removesubmit">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update' , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                    </div>
                </div>
    </div>
    
    
    
    <div class="row">
        <div class="col-lg-12">  
        <?php 
            $dataProvider = new ActiveDataProvider([
                'query' => Lineitembudgetobject::find(),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        ?>
        </div>
    </div>
    
                

    <?php 
        //print_r(Yii::$app->authManager->getRolesByUser(1));
        //echo '<br/>';
        //print_r(Yii::$app->user->identity->profile->division_id);
    ?>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-12">
        <div id="add-expenditure-objects">
            <div class="popup-container">
                <div class="mypopup" style="background-color: #BACBE8!important;">
                    <div class="col-lg-12">
                        <h1 style="text-align: center">Select Expenditure Objects</h1>
                        <div class="row">
                            <div class="col-lg-12">  
                            <?php 
                                $dataProvider = new ActiveDataProvider([
                                    'query' => Expenditureobject::find(),
                                    'pagination' => false,
                                ]);
                                $searchModel = new ExpenditureobjectSearch();
                            ?>
                            <?php echo GridView::widget([
                                    'dataProvider'=> $dataProvider,
                                    'filterModel' => $searchModel,
                                    'columns' => [
                                        [
                                            'class' => 'kartik\grid\CheckboxColumn',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],
                                        [
                                            'attribute'=>'expenditureSubClass.expenditureClass.expenditure_class_id', 
                                            'width'=>'250px',
                                            'value'=>function ($model, $key, $index, $widget) { 
                                                return $model->expenditureSubClass->expenditureClass->name;
                                            },
                                            'group'=>true,  // enable grouping,
                                            'groupedRow'=>true,                    // move grouped column to a single grouped row
                                            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                                        ],
                                        [
                                            'attribute'=>'expenditure_sub_class_id', 
                                            'width'=>'250px',
                                            'value'=>function ($model, $key, $index, $widget) { 
                                                return $model->expenditureSubClass->name;
                                            },
                                            'group'=>true,  // enable grouping,
                                            'groupedRow'=>true,                    // move grouped column to a single grouped row
                                        ],
                                        'name', 
                                        'object_code'
                                    ],

                                    'responsive'=>true,
                                    'hover'=>true
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
