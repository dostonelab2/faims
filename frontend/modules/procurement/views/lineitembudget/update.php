<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudget */

$this->title = 'Update Lineitembudget: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lineitembudgets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->line_item_budget_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lineitembudget-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
                'listTypes'=>$listTypes,
                'listSubTypes' => $listSubTypes,
                'listDivisions'=>$listDivisions,
                'listSections'=>$listSections,
                'listProject'=>$listProject,
                'listProgram'=>$listProgram,
        'model' => $model,
    ]) ?>

</div>
