<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "doctor".
 *
 * @property integer $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property string $showInApp
 * @property integer $admin
 */
class Doctor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doctor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','login','password'], 'required'],
            [['name'], 'string', 'max' => 512],
            [['login'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 255],
            [['showInApp','admin'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'login' => 'Login',
            'password' => 'Password',
            'showInApp' => 'Show In App',
            'showInAppDesc' => 'Show In App',
            'admin' => 'Administrator',
        ];
    }

    public function getNumPatients() {
        return Patient::find()->where(['id_doctor'=>$this->id])->count();
    }

    /**
     * @return Patient[]
     */
    public function getPatients() {
        return Patient::find()->where(['id_doctor'=>$this->id])->all();
    }

    public function getShowInAppDesc() {
        $options = Doctor::getShowInAppOptions();
        return isset($options[$this->showInApp]) ? $options[$this->showInApp] : "";
    }

    public function getAdminDesc() {
        $options = Doctor::getAdminOptions();
        return isset($options[$this->admin]) ? $options[$this->admin] : "";
    }

    /**
     * @param int $number
     * @return SleepLog[]
     */
    public function getLastPatientActivities($number = 10) {
        return SleepLog::find()
            ->joinWith('patient')
            ->where(['patient.id_doctor'=>$this->id])
            ->orderBy('timeIni')
            ->limit($number)
            ->all();
    }

    public static function getShowInAppOptions() {
        return [0 => 'No', 1 => 'Yes'];
    }

    public static function getAdminOptions() {
        return [0 => 'No', 1 => 'Yes'];
    }

}
