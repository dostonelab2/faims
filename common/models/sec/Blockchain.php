<?php

namespace common\models\sec;

use Yii;

use common\models\finance\Request;
/**
 * This is the model class for table "tbl_blockchain".
 *
 * @property integer $blockchain_id
 * @property integer $index_id
 * @property string $scope
 * @property string $timestamp
 * @property string $data
 * @property string $previoushash
 * @property string $hash
 * @property integer $nonce
 */
class Blockchain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_blockchain';
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
            [['index_id', 'scope', 'data', 'hash', 'nonce'], 'required'],
            [['index_id', 'nonce'], 'integer'],
            [['timestamp'], 'safe'],
            [['scope'], 'string', 'max' => 25],
            [['data'], 'string', 'max' => 256],
            [['previoushash', 'hash'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'blockchain_id' => 'Blockchain ID',
            'index_id' => 'Index ID',
            'scope' => 'Scope',
            'timestamp' => 'Timestamp',
            'data' => 'Data',
            'previoushash' => 'Previoushash',
            'hash' => 'Hash',
            'nonce' => 'Nonce',
        ];
    }
    
    /**
     * Creates the genesis block.
     */
    public static function createBlock($index, $scope, $data)
    {
        date_default_timezone_set('Asia/Manila');
        $timestamp = time();
        
        //$request = Request::findOne(1);
        //$index = $request->request_id;

        //$data = $request->request_number.':'.$request->request_date.':'.$request->request_type_id.':'.$request->payee_id.':'.$request->particulars.':'.$request->amount.':'.$request->status_id;
        
        $block = new Blockchain();
        $block->index_id = $index;
        $block->scope = $scope;
        $block->timestamp = $timestamp;
        $block->data = $data;
        //$block->previousHash = $previousHash;
        $block->hash = $block->calculateHash();
        $block->nonce = $timestamp;
        $block->save();
        
        return $block;
    }
    
    /**
     * Gets the last block of the chain.
     */
    public static function getLastBlock($index, $scope)
    {
        $block = Blockchain::find()->where(['index_id' => $index, 'scope' => $scope])->orderBy(['blockchain_id' => SORT_DESC])->one();
        return $block;
        //return $this->chain[count($this->chain)-1];
    }

    public function calculateHash2($index, $scope, $data)
    {
        $block = Blockchain::find()->where(['index_id' => $index, 'scope' => $scope])->orderBy(['blockchain_id' => SORT_DESC])->one();
        $this->previoushash = $block ? $block->hash : NULL;
        
        return hash("sha256", $index.$this->previoushash.$this->timestamp.((string)$this->data).$this->nonce);
    }
    
    public function calculateHash()
    {
        $block = Blockchain::find()->where(['index_id' => $this->index_id, 'scope' => $this->scope])->orderBy(['blockchain_id' => SORT_DESC])->one();
        $this->previoushash = $block ? $block->hash : NULL;
        
        return hash("sha256", $this->index_id.$this->previoushash.$this->timestamp.((string)$this->data).$this->nonce);
    }
    
    public static function getChain($index, $scope)
    {
        return Blockchain::find()->where(['index_id' => $index, 'scope' => $scope])->asArray()->all();
    }
}
