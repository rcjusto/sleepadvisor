<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class PasswordForm extends Model
{
    public $password;
    public $re_password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['re_password'], 'compare', 'compareAttribute'=>'password'],
        ];
    }

}
