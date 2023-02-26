<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Reportsignatory */

$this->title = 'Update Reportsignatory: ' . $model->report_signatory_id;
$this->params['breadcrumbs'][] = ['label' => 'Reportsignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->report_signatory_id, 'url' => ['view', 'id' => $model->report_signatory_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reportsignatory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
