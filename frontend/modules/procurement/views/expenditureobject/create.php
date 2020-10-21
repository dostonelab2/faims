<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Expenditureobject */

$this->title = 'Create Expenditureobject';
$this->params['breadcrumbs'][] = ['label' => 'Expenditureobjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenditureobject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
