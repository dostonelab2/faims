<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Attachmenttype */

$this->title = 'Create Attachmenttype';
$this->params['breadcrumbs'][] = ['label' => 'Attachmenttypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attachmenttype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
