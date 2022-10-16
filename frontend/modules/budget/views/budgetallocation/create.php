<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocation */

$this->title = 'Create Budgetallocation';
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
