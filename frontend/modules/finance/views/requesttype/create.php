<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Requesttype */

$this->title = 'Create Requesttype';
$this->params['breadcrumbs'][] = ['label' => 'Requesttypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requesttype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
