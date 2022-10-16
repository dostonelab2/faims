<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\cashier\Creditor */

$this->title = 'Create Creditor';
$this->params['breadcrumbs'][] = ['label' => 'Creditors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creditor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
