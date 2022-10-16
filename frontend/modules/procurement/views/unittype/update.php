<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Unittype */

$this->title = 'Update Unittype: ' . $model->unit_type_id;
$this->params['breadcrumbs'][] = ['label' => 'Unittypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->unit_type_id, 'url' => ['view', 'id' => $model->unit_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="unittype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
