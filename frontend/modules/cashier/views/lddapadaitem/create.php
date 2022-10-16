<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapadaitem */

$this->title = 'Create Lddapadaitem';
$this->params['breadcrumbs'][] = ['label' => 'Lddapadaitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lddapadaitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
