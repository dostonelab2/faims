<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\system\Appsettings */

$this->title = 'Create Appsettings';
$this->params['breadcrumbs'][] = ['label' => 'Appsettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appsettings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
