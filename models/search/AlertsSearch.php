<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Alerts;

/**
 * AlertsSearch represents the model behind the search form about `app\models\Alerts`.
 */
class AlertsSearch extends Alerts
{
    public function rules()
    {
        return [
            [['id', 'message'], 'safe'],
            [['deleted', 'modified', 'versionNumber', 'id_activity', 'cond_gt', 'cond_lt', 'level'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Alerts::find();

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
            'cond_gt' => $this->cond_gt,
            'cond_lt' => $this->cond_lt,
            'level' => $this->level,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
