<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Requesttypeattachment */

$this->title = 'Create Requesttypeattachment';
$this->params['breadcrumbs'][] = ['label' => 'Requesttypeattachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requesttypeattachment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
