<?php

namespace app\modules\doctor;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\doctor\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->errorHandler->errorAction = 'doctor/default/error';
    }
}
