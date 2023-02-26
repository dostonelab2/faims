<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Reportsignatory */

$this->title = 'Create Reportsignatory';
$this->params['breadcrumbs'][] = ['label' => 'Reportsignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reportsignatory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
