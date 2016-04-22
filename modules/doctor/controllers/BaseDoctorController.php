<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 9/18/2015
 * Time: 3:45 PM
 */

namespace app\modules\doctor\controllers;


use app\components\AccessControl;
use app\models\User;
use Yii;
use yii\web\Controller;

class BaseDoctorController extends Controller {

    public $layout ="doctors";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $hasAccess = !Yii::$app->user->isGuest && Yii::$app->user->identity->role == User::ROLE_DOCTOR;
                            return $hasAccess;
                        }
                    ],
                ],
            ],
        ];
    }



}