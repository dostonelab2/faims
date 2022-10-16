<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\budget\Obligation */

$this->title = 'Create Obligation';
$this->params['breadcrumbs'][] = ['label' => 'Obligations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obligation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listObligationrequests' => $listObligationrequests,
    ]) ?>

</div>
