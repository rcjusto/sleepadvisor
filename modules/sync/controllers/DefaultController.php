<?php

namespace app\modules\sync\controllers;

use app\models\Doctor;
use app\models\Patient;
use yii\web\Controller;

class DefaultController extends Controller
{

    const DR_SILBERMAN_ID = 3;

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionLogin()
    {

        \Yii::$app->response->format = 'json';

        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

        \Yii::info("Login requested: un -> $username, pw -> $password", 'sync');

        $result = array();
        /** @var Patient $patient */
        $patient = Patient::find()->where(['username' => $username])->one();
        if (!is_null($patient)) {
            if ($password == $patient->password) {
                $result['result'] = 'ok';
                $result['username'] = $patient->username;
                $result['password'] = $patient->password;
                if (!empty($patient->name)) $result['name'] = $patient->name;
                if (!empty($patient->email)) $result['email'] = $patient->email;
                if (!empty($patient->id_doctor)) $result['doctor'] = $patient->id_doctor . '';
                if (!empty($patient->hour_adjust)) $result['time_adjust'] = $patient->hour_adjust;
            } else {
                $result['result'] = 'error';
                $result['message'] = 'Wrong password';
            }
        } else {
            $result['result'] = 'error';
            $result['message'] = 'User name not found';
        }

        return $result;
    }

    public function actionProfile()
    {
        \Yii::$app->response->format = 'json';

        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        $doctor = isset($_REQUEST['doctor']) ? $_REQUEST['doctor'] : '';
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $time_adjust = isset($_REQUEST['time_adjust']) ? $_REQUEST['time_adjust'] : '';

        \Yii::info("Profile requested: un -> $username, pw -> $password, name -> $name, email -> $email, doctor -> $doctor, adjust -> $time_adjust", 'sync');

        if (empty($username))
            return ['result' => 'error', 'message' => 'Username is required'];

        /** @var Patient $patient */
        $patient = Patient::find()->where(['username' => $username])->one();
        if (is_null($patient))
            return ['result' => 'error', 'message' => 'Username not found'];

        if (!empty($password)) $patient->password = $password;
        if (!empty($time_adjust)) $patient->hour_adjust = $time_adjust;
        if (!empty($name)) $patient->name = $name;
        $patient->id_doctor = (!empty($doctor)) ? $doctor : 0;
        if (!empty($email)) $patient->email = $email;
        if ($patient->save()) {
            $result = [
                'result' => 'ok',
                'username' => $username,
                'password' => $password,
            ];
            if (!empty($patient->name)) $result['name'] = $patient->name;
            if (!empty($patient->email)) $result['email'] = $patient->email;
            if (!empty($patient->id_doctor)) $result['doctor'] = $patient->id_doctor;
            if (!empty($patient->hour_adjust)) $result['time_adjust'] = $patient->hour_adjust;
            return $result;
        } else {
            return ['result' => 'error', 'message' => implode('. ', $patient->getFirstErrors())];
        }
    }

    public function actionRegister()
    {
        \Yii::$app->response->format = 'json';

        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        $doctor = isset($_REQUEST['doctor']) ? $_REQUEST['doctor'] : '';
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';

        \Yii::info("Register requested: un -> $username, pw -> $password, name -> $name, email -> $email, doctor -> $doctor", 'sync');

        if (empty($username))
            return ['result' => 'error', 'message' => 'Username is required'];

        if (empty($password))
            return ['result' => 'error', 'message' => 'Password is required'];

        if (Patient::find()->where(['username' => $username])->exists())
            return ['result' => 'error', 'message' => 'Username already exists'];

        $patient = new Patient();
        $patient->username = $username;
        $patient->password = $password;
        $patient->hour_adjust = -4;
        if (!empty($name)) $patient->name = $name;
        if (!empty($doctor)) $patient->id_doctor = $doctor;
        if (!empty($email)) $patient->email = $email;
        if ($patient->save()) {
            $result = [
                'result' => 'ok',
                'username' => $username,
                'password' => $password,
            ];
            if (!empty($patient->name)) $result['name'] = $patient->name;
            if (!empty($patient->email)) $result['email'] = $patient->email;
            if (!empty($patient->id_doctor)) $result['doctor'] = $patient->id_doctor;
            if (!empty($patient->hour_adjust)) $result['time_adjust'] = $patient->hour_adjust;
            return $result;
        } else {
            return ['result' => 'error', 'message' => implode('. ', $patient->getFirstErrors())];
        }
    }


    public function actionDoctors()
    {
        \Yii::$app->response->format = 'json';

        $doctors = Doctor::find()->where(['showInApp'=>1])->all();
        $result = [];
        foreach($doctors as $doctor) {
            $result[$doctor->id] = $doctor->name;
        }
        return $result;
    }

    public function actionDoctor()
    {
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $doctor = isset($_REQUEST['doctor']) ? $_REQUEST['doctor'] : '';

        /** @var Patient $patient */
        $patient = Patient::find()->where(['username' => $username])->one();
        if (!is_null($patient)) {
            $patient->id_doctor = ($doctor=='Y') ? self::DR_SILBERMAN_ID : null;
            $patient->save();
        }

        return false;
    }

}
