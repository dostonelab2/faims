<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestpayrollitem */

$this->title = 'Create Requestpayrollitem';
$this->params['breadcrumbs'][] = ['label' => 'Requestpayrollitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requestpayrollitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
