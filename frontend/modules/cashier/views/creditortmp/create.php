<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\cashier\Creditortmp */

$this->title = 'Create Creditortmp';
$this->params['breadcrumbs'][] = ['label' => 'Creditortmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creditortmp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
