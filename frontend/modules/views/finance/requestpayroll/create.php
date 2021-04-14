<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestpayroll */

$this->title = 'Create Requestpayroll';
$this->params['breadcrumbs'][] = ['label' => 'Requestpayrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requestpayroll-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
