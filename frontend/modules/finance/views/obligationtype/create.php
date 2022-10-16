<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Obligationtype */

$this->title = 'Create Obligationtype';
$this->params['breadcrumbs'][] = ['label' => 'Obligationtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obligationtype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
