<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Expenditureobject */

$this->title = 'Update Expenditureobject: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expenditureobjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->expenditure_object_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expenditureobject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
