<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Creditortmp */

$this->title = 'Update Creditortmp: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Creditortmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->creditor_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="creditortmp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
