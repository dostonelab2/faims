<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Assignatory */

$this->title = 'Update Assignatory: ' . $model->assignatory_id;
$this->params['breadcrumbs'][] = ['label' => 'Assignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->assignatory_id, 'url' => ['view', 'id' => $model->assignatory_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="assignatory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listEmployee' => $listEmployee
    ]) ?>

</div>
