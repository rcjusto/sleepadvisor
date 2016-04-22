<?php

namespace app\modules\admin\controllers;


use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

class DefaultController extends BaseAdminController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError()
    {
        if (($exception = Yii::$app->errorHandler->exception) === null) {
            return '';
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = Yii::t('yii', 'An internal server error occurred.');
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render('error', [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }

}
