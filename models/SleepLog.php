<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sleep_log".
 *
 * @property string $id
 * @property integer $deleted
 * @property integer $modified
 * @property integer $versionNumber
 * @property string $timeIni
 * @property string $timeEnd
 * @property integer $activity
 * @property integer $option
 * @property integer $value
 * @property integer $id_patient
 * @property string $comments
 */
class SleepLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sleep_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'timeIni', 'activity'], 'required'],
            [['deleted', 'modified', 'versionNumber', 'timeIni', 'timeEnd', 'activity', 'option', 'value', 'id_patient'], 'integer'],
            [['comments'], 'string'],
            [['id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deleted' => 'Deleted',
            'modified' => 'Modified',
            'versionNumber' => 'Version Number',
            'timeIni' => 'Time Ini',
            'timeEnd' => 'Time End',
            'activity' => 'Activity',
            'option' => 'Option',
            'value' => 'Value',
            'comments' => 'Comments',
            'id_patient' => 'Patient',
        ];
    }

    public function getAdjustedDate() {
        $hour_adjust = $this->patient->hour_adjust;
        return $this->timeIni/1000 - 60*60*$hour_adjust;
    }

    public function getDate($format = 'm d, Y') {
        return gmdate($format, $this->timeIni/1000);
    }

    public function getHourIni($format = 'H:i') {
        return gmdate($format, $this->timeIni/1000);
    }

    public function getHourEnd($format = 'H:i') {
        return ($this->timeEnd>0) ? gmdate($format, $this->timeEnd/1000) : '';
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id_patient' => 'id_patient']);
    }

    private $_activityData = null;
    public function getActivityData($field) {
        if (is_null($this->_activityData)) {
            $this->_activityData = Activities::getActivity($this->activity);
        }
        return !is_null($this->_activityData) && isset($this->_activityData[$field]) ? $this->_activityData[$field] : '';
    }

    public function getImage() {
        return Yii::$app->request->baseUrl . '/images/' . $this->getActivityData('image');
    }

    public function getDuration() {
        $result = '';
        if ($this->timeEnd>$this->timeIni) {
            $duration = ($this->timeEnd / 1000 - $this->timeIni / 1000) ;
            $hours = floor($duration / 3600);
            $minutes = (($duration/60) % 60);
            if ($hours>0) $result .= $hours . ' Hrs ';
            if ($minutes>0) $result .= $minutes . ' Min ';
        }
        return $result;
    }

    public function getDetails() {
        switch($this->activity) {
            case 6:
                $options = $this->getActivityData('options');
                if (isset($options) && isset($options[$this->option])) return $options[$this->option];
                break;
            case 7:
                $options = $this->getActivityData('options');
                if (isset($options) && isset($options[$this->option])) return $this->value . ' ' .$options[$this->option];
                break;
            case 8:
                return $this->value . ' cigarettes';
                break;
            case 9:
                $res = $this->value . ' pills';
                if (!empty($this->comments)) $res .= ' of ' . $this->comments;
                return $res;
                break;
            case 10:
                return $this->value . ' drinks';
                break;
        }
        return '';
    }

}
