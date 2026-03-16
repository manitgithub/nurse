<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AcademicService;

class AcademicServiceSearch extends AcademicService
{
    public function rules()
    {
        return [
            [['id', 'fiscal_year'], 'integer'],
            [['activity_name', 'status', 'start_date', 'end_date', 'budget_source', 'project_type'], 'safe'],
            [['budget_amount', 'latitude', 'longitude'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AcademicService::find();

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
            'fiscal_year' => $this->fiscal_year,
            'budget_amount' => $this->budget_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $query->andFilterWhere(['like', 'activity_name', $this->activity_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'budget_source', $this->budget_source])
            ->andFilterWhere(['like', 'project_type', $this->project_type]);

        return $dataProvider;
    }
}
