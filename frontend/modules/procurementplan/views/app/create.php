<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */

$this->title = 'Create Ppmpitem';
$this->params['breadcrumbs'][] = ['label' => 'Ppmpitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmpitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
