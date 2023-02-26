<?php

namespace common\models\system;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\system\Appsettings;

/**
 * AppsettingsSearch represents the model behind the search form about `common\models\system\Appsettings`.
 */
class AppsettingsSearch extends Appsettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_id', 'index_id'], 'integer'],
            [['module_id', 'name'], 'safe'],
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
        $query = Appsettings::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['module_id'=>SORT_ASC, 'name'=>SORT_ASC, 'division_id'=>SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'setting_id' => $this->setting_id,
            'index_id' => $this->index_id,
        ]);

        $query->andFilterWhere(['like', 'module_id', $this->module_id])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
