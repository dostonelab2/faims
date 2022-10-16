<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Expenditure */

$this->title = 'Update Expenditure: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expenditures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->expenditure_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expenditure-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
