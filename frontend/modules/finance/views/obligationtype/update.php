<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Obligationtype */

$this->title = 'Update Obligationtype: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Obligationtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="obligationtype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
