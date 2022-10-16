<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Unittype */

$this->title = 'Create Unittype';
$this->params['breadcrumbs'][] = ['label' => 'Unittypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unittype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
