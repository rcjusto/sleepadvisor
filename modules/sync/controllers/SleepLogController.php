<?php

namespace app\modules\sync\controllers;

use app\models\Patient;
use app\models\SleepLog;
use yii\helpers\Json;
use yii\web\Controller;

class SleepLogController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGet()
    {
        \Yii::$app->response->format = 'json';

        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : 0;
        /** @var Patient $patient */
        $patient = Patient::find()->where(['username' => $username])->one();

        $results = [];
        $timestamp = 0;
        if (!is_null($patient)) {
            $last = isset($_REQUEST['last']) ? $_REQUEST['last'] : 0;
            /** @var SleepLog[] $list */
            $list = SleepLog::find()->where('id_patient=:p and modified>:last', [':p' => $patient->id_patient, ':last' => $last])->orderBy('modified')->all();
            foreach($list as $log) {
                $results[] = $log;
                if ($log->modified>$timestamp) $timestamp = $log->modified;
            }
        }
        return ['results'=>$results, 'timestamp'=>$timestamp];
    }

    public function actionPut()
    {
        \Yii::$app->response->format = 'json';

        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : 0;
        /** @var Patient $patient */
        $patient = Patient::find()->where(['username' => $username])->one();

        if (!is_null($patient)) {

            \Yii::info("Put sleeplog, patient: $username ", 'sync');

            $dataStr = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
            \Yii::info("Data: $dataStr ", 'sync');

            $data = Json::decode($dataStr);
            $model = SleepLog::findOne($data['id']);
            if (is_null($model)) {
                $model = new SleepLog();
            }
            $model->attributes = $data;
            $model->id_patient = $patient->id_patient;
            $model->modified = time();
            if ($model->save()) {
                $result = $model->attributes;
                $result['result'] = 'ok';
                return $model->modified;
            } else {
                return array('result' => 'error', 'message' => implode('. ', $model->getFirstErrors()));
            }
        } else {
            return array('result' => 'error', 'message' => 'Patient not found');
        }
    }

    public function actionDel()
    {
        \Yii::$app->response->format = 'json';

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $model = SleepLog::findOne($id);
        if (!is_null($model)) {
            $model->deleted = 1;
            $model->modified = time();
            $model->save();
        }

        return array('result' => 'ok');
    }


}
