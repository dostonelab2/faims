<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "tbl_app_settings".
 *
 * @property integer $setting_id
 * @property string $module_id
 * @property string $name
 * @property string $index_type
 * @property integer $division_id
 * @property string $index_value
 * @property integer $index_id
 */
class Appsettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_app_settings';
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
            [['module_id', 'name', 'index_type'], 'required'],
            [['division_id', 'index_id'], 'integer'],
            [['module_id', 'name', 'index_type'], 'string', 'max' => 25],
            [['index_value'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'module_id' => 'Module ID',
            'name' => 'Name',
            'index_type' => 'Index Type',
            'division_id' => 'Division ID',
            'index_value' => 'Index Value',
            'index_id' => 'Index ID',
        ];
    }

    public function getProfile()  
    {  
      return $this->hasOne(Profile::className(), ['user_id' => 'index_id']);  
    }
}
