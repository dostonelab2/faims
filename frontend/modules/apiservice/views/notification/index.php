<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\apiservice\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
echo $_SERVER['SERVER_NAME'];
?>

<div class="notification-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Notification', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'notification_id',
            'hash',
            'sender',
            'recipient',
            'status',
             'title',
            // 'message:ntext',
            // 'via',
            // 'created_at',
            // 'module',
            // 'action',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>


<pre>
<?php print_r($response); ?>
</pre>