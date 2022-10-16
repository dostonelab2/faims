<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\budget\Allocationadjustment */

$this->title = 'Create Allocationadjustment';
$this->params['breadcrumbs'][] = ['label' => 'Allocationadjustments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocationadjustment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'budgetallocationitem'=>$budgetallocationitem,
        'listSections'=>$listSections,
        'sections'=>$sections,
        'budgetallocationitems'=>$budgetallocationitems,
    ]) ?>

</div>
