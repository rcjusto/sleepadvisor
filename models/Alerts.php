<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "alerts".
 *
 * @property string $id
 * @property integer $deleted
 * @property integer $modified
 * @property integer $versionNumber
 * @property integer $id_activity
 * @property integer $cond_gt
 * @property integer $cond_lt
 * @property string $message
 * @property integer $level
 */
class Alerts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alerts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deleted', 'modified', 'versionNumber', 'id_activity', 'cond_gt', 'cond_lt', 'level'], 'integer'],
            [['message'], 'string'],
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
            'id_activity' => 'Activity',
            'cond_gt' => 'Less Than',
            'cond_lt' => 'More Than',
            'message' => 'Message',
            'level' => 'Level',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if (empty($this->id)) $this->id = $this->getGUID();
            $this->deleted = 0;
            $this->modified = time();
            $this->versionNumber = 1;
        }
        return parent::beforeSave($insert);
    }

    function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);
            $charId = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = ''
                .substr($charId, 0, 8).$hyphen
                .substr($charId, 8, 4).$hyphen
                .substr($charId,12, 4).$hyphen
                .substr($charId,16, 4).$hyphen
                .substr($charId,20,12);
            return $uuid;
        }
    }


}
