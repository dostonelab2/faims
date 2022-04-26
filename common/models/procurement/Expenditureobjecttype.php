<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_expenditure_object_type".
 *
 * @property integer $expenditure_object_type_id
 * @property integer $expenditure_object_id
 * @property string $name
 * @property string $object_code
 */
class Expenditureobjecttype extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_expenditure_object_type';
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
            [['expenditure_object_id', 'name', 'object_code'], 'required'],
            [['expenditure_object_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['object_code'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expenditure_object_type_id' => 'Expenditure Object Type ID',
            'expenditure_object_id' => 'Expenditure Object ID',
            'name' => 'Name',
            'object_code' => 'Object Code',
        ];
    }
}
