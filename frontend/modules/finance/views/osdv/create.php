<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Osdv */

$this->title = 'Create Osdv';
$this->params['breadcrumbs'][] = ['label' => 'Osdvs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="osdv-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'requests' => $requests,
    ]) ?>

</div>
