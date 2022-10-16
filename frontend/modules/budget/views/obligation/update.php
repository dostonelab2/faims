<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Obligation */

$this->title = 'Update Obligation: ' . $model->obligation_id;
$this->params['breadcrumbs'][] = ['label' => 'Obligations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->obligation_id, 'url' => ['view', 'id' => $model->obligation_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="obligation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
