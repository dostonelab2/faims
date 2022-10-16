<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudgetobjectdetails */

$this->title = 'Update Lineitembudgetobjectdetails: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lineitembudgetobjectdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->line_item_budget_object_details_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lineitembudgetobjectdetails-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
