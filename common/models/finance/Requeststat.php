<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_request_stat".
 *
 * @property integer $request_stat_id
 * @property integer $request_id
 * @property integer $status_id
 * @property string $number_of_days
 */
class Requeststat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_request_stat';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('procurementdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'status_id', 'number_of_days'], 'required'],
            [['request_id', 'status_id', 'number_of_days'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_stat_id' => 'Request Stat ID',
            'request_id' => 'Request ID',
            'status_id' => 'Status ID',
            'number_of_days' => 'Number Of Days',
        ];
    }

    public function getRequest()  
    {  
      return $this->hasOne(Request::className(), ['request_id' => 'request_id']);  
    }

    function exist($request_id, $status_id)
    {
        $model = Requeststat::find()
            ->where(['request_id' => $request_id, 'status_id' => $status_id])
            ->one();

        $exist = $model ? true : false;
        return $exist;
    }
}
