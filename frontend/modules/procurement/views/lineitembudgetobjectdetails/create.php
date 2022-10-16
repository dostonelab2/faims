<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudgetobjectdetails */

$this->title = 'Create Lineitembudgetobjectdetails';
$this->params['breadcrumbs'][] = ['label' => 'Lineitembudgetobjectdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lineitembudgetobjectdetails-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id'=>$id,
        'objectdetails' => $objectdetails,
        'objectdetailscategory' => $objectdetailscategory,
    ]) ?>

</div>
