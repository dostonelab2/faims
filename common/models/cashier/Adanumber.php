<?php

namespace common\models\cashier;

use Yii;

/**
 * This is the model class for table "tbl_ada_number".
 *
 * @property integer $ada_number_id
 * @property integer $type_id
 * @property integer $prefix
 * @property integer $year
 * @property integer $month
 * @property integer $counter
 */
class Adanumber extends \yii\db\ActiveRecord
{
    public $ada_number;
    public $selected_keys;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ada_number';
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
            [['type_id', 'prefix', 'year', 'month', 'counter'], 'required'],
            [['type_id', 'prefix', 'year', 'month', 'counter'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ada_number_id' => 'Ada Number ID',
            'type_id' => 'Type ID',
            'prefix' => 'Prefix',
            'year' => 'Year',
            'month' => 'Month',
            'counter' => 'Counter',
        ];
    }
    
    static function getAdaNumber($typeId, $year, $month)
    {
        $day = date("d");
        $month = date("m");
        
        $ada = Adanumber::find()->where(['type_id' => $typeId, 'year' => $year, 'month' => $month])->orderBy(['ada_number_id' => SORT_DESC])->one();
        $counter = (int)$ada->counter + 1;
        return $ada->prefix.$month.(($typeId == 4) ? $day : '').str_pad($counter, 3, '0', STR_PAD_LEFT);
    }
}
