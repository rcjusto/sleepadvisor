<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SleepLog;

/**
 * SleepLogSearch represents the model behind the search form about `app\models\SleepLog`.
 */
class SleepLogSearch extends SleepLog
{
    public function rules()
    {
        return [
            [['id', 'comments'], 'safe'],
            [['deleted', 'modified', 'versionNumber', 'timeIni', 'timeEnd', 'activity', 'option', 'value'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SleepLog::find();

        $query->orderBy = ['timeIni'=>'asc'];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'deleted' => 0,
            'modified' => $this->modified,
            'versionNumber' => $this->versionNumber,
            'timeIni' => $this->timeIni,
            'timeEnd' => $this->timeEnd,
            'activity' => $this->activity,
            'option' => $this->option,
            'value' => $this->value,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
