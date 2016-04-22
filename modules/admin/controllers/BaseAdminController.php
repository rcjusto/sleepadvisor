<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 9/18/2015
 * Time: 3:48 PM
 */

namespace app\modules\admin\controllers;


use app\components\AccessControl;
use app\models\User;
use Yii;
use yii\web\Controller;

class BaseAdminController extends Controller {

    public $layout ="admin";

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
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->role == User::ROLE_ADMIN;
                        }
                    ],
                ],
            ],
        ];
    }


}