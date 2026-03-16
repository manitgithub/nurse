<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Innovation;

/**
 * InnovationSearch represents the model behind the search form of `app\models\Innovation`.
 */
class InnovationSearch extends Innovation
{
    /**
     * @var string
     */
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'invention_date', 'problem', 'process', 'results', 'advisor', 'developers', 'globalSearch'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Innovation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'invention_date' => $this->invention_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'problem', $this->problem])
            ->andFilterWhere(['like', 'process', $this->process])
            ->andFilterWhere(['like', 'results', $this->results])
            ->andFilterWhere(['like', 'advisor', $this->advisor])
            ->andFilterWhere(['like', 'developers', $this->developers]);

        if (!empty($this->globalSearch)) {
            $query->andFilterWhere([
                'or',
                ['like', 'name', $this->globalSearch],
                ['like', 'advisor', $this->globalSearch],
                ['like', 'developers', $this->globalSearch],
            ]);
        }

        return $dataProvider;
    }
}
