<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\system\Appsettings */

$this->title = 'Update Appsettings: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Appsettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->setting_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="appsettings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
