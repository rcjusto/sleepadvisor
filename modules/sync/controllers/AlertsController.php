<?php

namespace app\modules\sync\controllers;

use app\models\Alerts;
use yii\helpers\Json;
use yii\web\Controller;

class AlertsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionGet()
    {
        \Yii::$app->response->format = 'json';

        $last = isset($_REQUEST['last']) ? $_REQUEST['last'] : 0;
        /** @var Alerts[] $list */
        $results = [];
        $timestamp = 0;
        $list = Alerts::find()->where('modified>:last', [':last'=>$last])->orderBy('modified')->all();
        foreach($list as $alert) {
            $results[] = $alert;
            if ($alert->modified>$timestamp) $timestamp = $alert->modified;
        }
        return ['results'=>$list, 'timestamp'=>$timestamp];
    }

    public function actionPut()
    {
        \Yii::$app->response->format = 'json';

        $dataStr = file_get_contents('php://input');
        $data = Json::decode($dataStr);
        $model = Alerts::findOne($data['id']);
        if (is_null($model)) {
            $model = new Alerts();
        }
        $model->attributes = $data;
        $model->modified = time();
        if ($model->save()) {
            $result = $model->attributes;
            $result['result'] = 'ok';
            return $model->modified;
        } else {
            return array('result'=>'error','message'=>implode('. ', $model->getFirstErrors()));
        }
    }

    public function actionDel()
    {
        \Yii::$app->response->format = 'json';

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $model = Alerts::findOne($id);
        if (!is_null($model)) {
            $model->deleted = 1;
            $model->modified = time();
            $model->save();
        }

        return array('result'=>'ok');
    }


}
