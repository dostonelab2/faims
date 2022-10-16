<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Accounttransaction */

$this->title = 'Create Accounttransaction';
$this->params['breadcrumbs'][] = ['label' => 'Accounttransactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounttransaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
