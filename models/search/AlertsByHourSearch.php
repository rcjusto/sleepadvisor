<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AlertsByHour;

/**
 * AlertsByHourSearch represents the model behind the search form about `app\models\AlertsByHour`.
 */
class AlertsByHourSearch extends AlertsByHour
{
    public function rules()
    {
        return [
            [['id', 'message'], 'safe'],
            [['deleted', 'modified', 'versionNumber', 'id_activity', 'hours_before', 'level'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AlertsByHour::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'deleted' => $this->deleted,
            'modified' => $this->modified,
            'versionNumber' => $this->versionNumber,
            'id_activity' => $this->id_activity,
            'hours_before' => $this->hours_before,
            'level' => $this->level,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
