<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_officer_in_charge".
 *
 * @property integer $officer_in_charge_id
 * @property integer $division_id
 * @property string $scope
 * @property string $box
 * @property integer $user_id
 * @property string $start_date
 * @property string $end_date
 */
class Officerincharge extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_officer_in_charge';
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
            [['division_id', 'scope', 'box', 'user_id', 'start_date', 'end_date'], 'required'],
            [['division_id', 'user_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['scope'], 'string', 'max' => 25],
            [['box'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'officer_in_charge_id' => 'Officer In Charge ID',
            'division_id' => 'Division ID',
            'scope' => 'Scope',
            'box' => 'Box',
            'user_id' => 'User ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }
}
