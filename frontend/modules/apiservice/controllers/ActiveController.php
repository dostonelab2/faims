<?php
namespace frontend\modules\apiservice\controllers;

use common\models\apiservice\Notification;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

class ActiveController extends \yii\rest\ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];

        return $behaviors;
    }

    /**
     *
     *
     * @param string $action
     * @param Post|Comment $model
     * @param array $params
     * @throws ForbiddenHttpException
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete']) && $model->created_by !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You do not have permission to change this record");
        }
    }
}