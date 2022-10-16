<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocationitem */

$this->title = 'Create Budgetallocationitem';
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocationitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocationitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
