<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Fundsource */

$this->title = 'Update Fundsource: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fundsources', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->fund_source_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fundsource-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
