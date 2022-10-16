<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudget */

$this->title = 'Create Line Item Budget';
$this->params['breadcrumbs'][] = ['label' => 'Lineitembudgets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lineitembudget-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->renderAjax('_modal', [
        'model' => $model,
        'listTypes' => $listTypes,
        'listSubTypes' => $listSubTypes,
        'listDivisions'=>$listDivisions,
        'listSections'=>$listSections,
    ]) ?>

</div>
