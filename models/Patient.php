<?php

namespace app\models;

use Yii;
use yii\helpers\Json;


/**
 * This is the model class for table "patient".
 *
 * @property integer $id_patient
 * @property integer $id_doctor
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $name
 * @property integer $hour_adjust
 * @property integer $allow_dossage
 * @property string $phone
 * @property string $created_time
 */
class Patient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'patient';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_doctor', 'hour_adjust', 'allow_dossage'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 255],
            [['email', 'name'], 'string', 'max' => 512],
            [['phone'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_patient' => 'Id Patient',
            'id_doctor' => 'Id Doctor',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'name' => 'Name',
            'hour_adjust' => 'Hour Adjust',
            'allow_dossage' => 'Allow Dossage',
            'phone' => 'Phone',
        ];
    }

    public function beforeSave($insert)
    {
        if (empty($this->created_time)) $this->created_time = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    public function getSleepLogs()
    {
        return $this->hasMany(SleepLog::className(), ['id_patient' => 'id_patient', 'deleted' => 0])->inverseOf('patient');;
    }

    public function getFirstDate()
    {
        return SleepLog::find()->where(['id_patient' => $this->id_patient, 'deleted' => 0])->min('timeIni');
    }

    public function getLastDate()
    {
        return SleepLog::find()->where(['id_patient' => $this->id_patient, 'deleted' => 0])->max('timeIni');
    }

    public function getDoctorName()
    {
        $doctor = Doctor::findOne($this->id_doctor);
        return is_null($doctor) ? '' : $doctor->name;
    }

    public function getSleepLogsForDays($day1, $day2, $adjustedHours = true)
    {
        $params = [
            ':pat' => $this->id_patient,
            ':tini' => $this->getDayIni($day1, $adjustedHours ? $this->hour_adjust : 0) * 1000,
            ':tend' => $this->getDayEnd($day2, $adjustedHours ? $this->hour_adjust : 0) * 1000,
        ];
        return SleepLog::find()->where('deleted=0 and id_patient=:pat AND timeIni>=:tini AND timeIni<:tend', $params)->orderBy(['timeIni' => 'asc'])->all();
    }

    public function hasSleepLogsAfter($day, $adjustedHours = true)
    {
        $params = [
            ':pat' => $this->id_patient,
            ':tini' => $this->getDayIni($day, $adjustedHours ? $this->hour_adjust : 0) * 1000,
        ];
        return SleepLog::find()->where('deleted=0 and id_patient=:pat AND timeIni>:tini', $params)->exists();
    }

    public function hasSleepLogsBefore($day, $adjustedHours = true)
    {
        $params = [
            ':pat' => $this->id_patient,
            ':tini' => $this->getDayEnd($day, $adjustedHours ? $this->hour_adjust : 0) * 1000,
        ];
        return SleepLog::find()->where('deleted=0 and id_patient=:pat AND timeIni<:tini', $params)->exists();
    }

    public function getLogByActivities($last_date = null)
    {
        date_default_timezone_set('UTC');
        if (is_null($last_date)) $last_date = $this->getLastDate() / 1000;
        $first_date = strtotime(' -1 month', $last_date);
        $initial_date = $this->getFirstDate() / 1000;
        if ($first_date < $initial_date) $first_date = $initial_date;

        /** @var SleepLog[] $rows */
        $rows = SleepLog::find()->where('deleted=0 and id_patient=:pat and timeIni>=:ini_date and timeIni<=:end_date', [
            ':pat' => $this->id_patient,
            ':ini_date' => $first_date * 1000,
            ':end_date' => $last_date * 1000,
        ])->orderBy(['timeIni' => 'asc'])->all();

        $activities = array();
        $temp = array();
        foreach ($rows as $row) if ($row->getActivityData('type') == Activities::TYPE_TIMED) {
            $act = $row->activity;
            if (!array_key_exists($act, $activities)) $activities[$act] = $row->getActivityData('name');
            if (!isset($temp[$act])) $temp[$act] = array();
            $dur = $row->timeEnd / 1000 - $row->timeIni / 1000;
            $val = gmdate('H', $dur) * 60 + gmdate('i', $dur);
            $index = gmdate('Ymd', $row->getAdjustedDate()) + 0;
            if (array_key_exists($index, $temp[$act])) $temp[$act][$index] = $temp[$act][$index] + $val;
            else $temp[$act][$index] = $val;
        }

        $result = array();
        foreach ($activities as $actId => $actDesc) {
            $data = array();
            $fd = $this->getDayIni($first_date, 0);
            $ld = $this->getDayIni($last_date, 0);
            while ($fd <= $ld) {
                $index = gmdate('Ymd', $fd) + 0;
                $data[] = array(
                    $fd,
                    array_key_exists($index, $temp[$actId]) ? $temp[$actId][$index] : 0
                );
                $fd = strtotime('+1 days', $fd);
            }
            $result[$actId] = array(
                'label' => $actDesc,
                'data' => $data,
            );
        }

        return Json::encode($result);
    }

    private function getDayIni($day, $param)
    {
        $y = gmdate('Y', $day);
        $m = gmdate('m', $day);
        $d = gmdate('d', $day);
        return mktime(0, 0, 0, $m, $d, $y) + 60 * 60 * $param;
    }

    private function getDayEnd($day, $param)
    {
        $y = gmdate('Y', $day);
        $m = gmdate('m', $day);
        $d = gmdate('d', $day);
        return mktime(23, 59, 59, $m, $d, $y) + 60 * 60 * $param;
    }


    public function getLogByActivity($activity, $first_date, $last_date)
    {
        date_default_timezone_set('UTC');

        /** @var SleepLog[] $rows */
        $rows = SleepLog::find()->where('deleted=0 and id_patient=:pat and activity=:act and timeIni>=:ini_date and timeIni<=:end_date', [
            ':pat' => $this->id_patient,
            ':act' => $activity,
            ':ini_date' => $first_date * 1000,
            ':end_date' => $last_date * 1000,
        ])->orderBy(['timeIni' => 'asc'])->all();

        $activities = array();
        $temp = array();
        $temp[$activity] = array();
        foreach ($rows as $row) {
            $act = $row->activity;
            if (!array_key_exists($act, $activities)) $activities[$act] = $row->getActivityData('name');
            if (!isset($temp[$act])) $temp[$act] = array();
            $val = 0;
            if ($row->getActivityData('type') == Activities::TYPE_TIMED) {
                $dur = $row->timeEnd / 1000 - $row->timeIni / 1000;
                $val = gmdate('H', $dur) * 60 + gmdate('i', $dur);
            } elseif ($row->activity == 7) {
                // caffeine. get mg
                $val = Activities::getMgCaffeine($row->option) * $row->value;
            } else {
                $val = $row->value;
            }
            $index = gmdate('Ymd', $row->getAdjustedDate()) + 0;
            if (array_key_exists($index, $temp[$act])) $temp[$act][$index] = $temp[$act][$index] + $val;
            else $temp[$act][$index] = $val;
        }

        $actId = $activity;
        $actDesc = Activities::getActivity($actId)['name'];
        $data = array();
        $fd = $this->getDayIni($first_date, 0);
        $ld = $this->getDayIni($last_date, 0);
        while ($fd <= $ld) {
            $index = gmdate('Ymd', $fd) + 0;
            $data[] = array(
                $fd,
                array_key_exists($index, $temp[$actId]) ? $temp[$actId][$index] : 0
            );
            $fd = strtotime('+1 days', $fd);
        }
        return array(
            'label' => $actDesc,
            'data' => $data,
        );

    }

}
