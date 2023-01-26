<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_project_type".
 *
 * @property integer $project_type_id
 * @property integer $type_id
 * @property string $name
 * @property string $code
 */
class Projecttype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_project_type';
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
            [['type_id', 'name', 'code'], 'required'],
            [['type_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['code'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_type_id' => 'Project Type ID',
            'type_id' => 'Type ID',
            'name' => 'Name',
            'code' => 'Code',
        ];
    }
}
