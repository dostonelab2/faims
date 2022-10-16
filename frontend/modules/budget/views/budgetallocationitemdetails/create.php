<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocationitemdetails */

$this->title = 'Create Budgetallocationitemdetails';
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocationitemdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocationitemdetails-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
