<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Fundsource */

$this->title = 'Create Fundsource';
$this->params['breadcrumbs'][] = ['label' => 'Fundsources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fundsource-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
