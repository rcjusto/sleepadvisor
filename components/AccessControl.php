<?php

namespace app\components;

class AccessControl extends \yii\filters\AccessControl
{

    protected function denyAccess($user)
    {
        $user->loginRequired();
    }

}