<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\Request;

/**
 * RequestSearch represents the model behind the search form about `common\models\finance\Request`.
 */
class RequestosdvSearch extends Request
{
    /**
     * @inheritdoc
     */ 
    public function rules()
    {
        return [
            [['request_id', 'request_number', 'request_type_id', 'status_id', 'created_by'], 'integer'],
            [['request_date', 'payee_id', 'particulars'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Request::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['request_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'request_id' => $this->request_id,
            //'request_number' => $this->request_number,
            'request_date' => $this->request_date,
            'request_type_id' => $this->request_type_id,
            //'payee_id' => $this->payee_id,
            'amount' => $this->amount,
            //'status_id' => $this->status_id,
            'created_by' => $this->created_by,
        ]);
        $query->andFilterWhere(['>=', 'status_id', 50]);
        /*if(($this->user_id == 2)){
            $query->andFilterWhere(['in', 'payee_id', $this->payee_id])
                  ->andFilterWhere(['>=', 'status_id', $this->status_id]);
        }elseif(($this->user_id == 4)){
            //$query->andFilterWhere(['<>', 'payee_id', 129])
                //->andFilterWhere(['!=', 'payee_id', 129])
            $query->andFilterWhere(['in', 'division_id', $this->division_id])
                ->andFilterWhere(['>=', 'status_id', $this->status_id])
                ->andFilterWhere(['!=', 'payee_id', 129]);
            //->andFilterWhere(['<>', 'payee_id', $this->payee_id]);
        }else{
             $query->andFilterWhere(['like', 'payee_id', $this->payee_id])
                ->andFilterWhere(['>=', 'status_id', $this->status_id])
                ->andFilterWhere(['like', 'particulars', $this->particulars]);    
        }*/
        
        /*if(isset($this->status_id)){
            $query->andFilterWhere(['>=', 'status_id', 40]);
        }*/
        
        $query->andFilterWhere(['like', 'request_number', $this->request_number]);
        
        return $dataProvider;
    }
}
