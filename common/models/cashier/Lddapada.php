<?php

namespace common\models\cashier;

use Yii;

/**
 * This is the model class for table "tbl_lddapada".
 *
 * @property integer $lddapada_id
 * @property string $batch_number
 * @property string $batch_date
 * @property integer $certified_correct_id
 * @property integer $approved_id
 * @property integer $validated1_id
 * @property integer $validated2_id
 * @property string $created_by
 *
 * @property LddapadaItem[] $lddapadaItems
 */
class Lddapada extends \yii\db\ActiveRecord
{
    const CHANGED = 10;   
    const SAVED = 20;   
    const FUND_CLUSTER = '011011';
    const FUND_CLUSTER2 = '01101101';
    const FUND_CODE = '101';
    const ACCOUNT = 'LBP Centro 2195-9000-54';
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_lddapada';
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
            [['batch_number', 'batch_date', 'certified_correct_id', 'approved_id', 'validated1_id', 'validated2_id'], 'required'],
            [['batch_date'], 'safe'],
            [['certified_correct_id', 'approved_id', 'validated1_id', 'validated2_id'], 'integer'],
            [['batch_number', 'created_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lddapada_id' => 'Lddapada ID',
            'batch_number' => 'LDDAP-ADA No.',
            'batch_date' => 'Date',
            'certified_correct_id' => 'Certified Correct ID',
            'approved_id' => 'Approved ID',
            'validated1_id' => 'Validated1 ID',
            'validated2_id' => 'Validated2 ID',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLddapadaItems()
    {
        return $this->hasMany(Lddapadaitem::className(), ['lddapada_id' => 'lddapada_id'])->andOnCondition(['active' => 1]);
    }
   
    
    static function generateBatchNumber1()
    {
        $year = date("y", strtotime("now"));
        $month = date("m", strtotime("now"));
        
        $start_date = date("Y-m-d", strtotime($year.'-'.$month.'-1'));
        $end_date = date("Y-m-t", strtotime($start_date));

        $count = Lddapada::find()->where(['between', 'batch_date', $start_date, $end_date])->count();
        $count += 1;
    
        return $year.'-'.$month.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    
    static function generateBatchNumber()
    {
        $year = date("Y", strtotime("now"));
        $month = date("m", strtotime("now"));
        
        $start_date = date("Y-m-d", strtotime($year.'-'.$month.'-1'));
        $end_date = date("Y-m-t", strtotime($start_date));

        $count = Lddapada::find()->where(['between', 'batch_date', $start_date, $end_date])->count();
        $count += 1;
    
        return Lddapada::FUND_CLUSTER.'-'.$month.'-'.str_pad($count, 3, '0', STR_PAD_LEFT).'-'.$year;
    }
    
    public function Batchnumber($typeId)
    {
        $year = date("Y", strtotime("now"));
        $month = date("m", strtotime("now"));
        
        $number = Lddapada::find()->where(['type_id' => $typeId, 'year(batch_date)' => date("Y", strtotime($year))])->orderBy(['lddapada_id' => SORT_DESC])->one();
        
        $batch = explode('-', $number->batch_number);
        $count = (int)$batch[2] + 1;
    
        return Lddapada::FUND_CLUSTER.'-'.$month.'-'.str_pad($count, 3, '0', STR_PAD_LEFT).'-'.$year.(($typeId == 4) ? 'TF' : '');
    }
    
    public function getTotal()
    {
        $items = Lddapadaitem::find()->where(['lddapada_id' => $this->lddapada_id, 'active' => 1])->all();
        $runningtotal = 0;
        foreach($items as $item)
        {
            $runningtotal += $item->osdv->getGrossamount();
        }
        return $runningtotal;
    }
    
    public function getTaxtotal()
    {
        $items = Lddapadaitem::find()->where(['lddapada_id' => $this->lddapada_id, 'active' => 1])->all();
        $runningtotal = 0;
        foreach($items as $item)
        {
            $runningtotal += $item->osdv->getTax();
        }
        return $runningtotal;
    }
    
    public function getNettotal()
    {
        $items = Lddapadaitem::find()->where(['lddapada_id' => $this->lddapada_id, 'active' => 1])->all();
        $runningtotal = 0;
        foreach($items as $item)
        {
            $runningtotal += $item->osdv->getNetamount();
        }
        return $runningtotal;
    }
}
