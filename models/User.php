<?php

namespace app\models;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{

    const ROLE_ADMIN = 'admin';
    const ROLE_DOCTOR = 'doctor';

    public $id;
    public $name;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $role;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $arr = explode('-',$id);
        $role = $arr[0];
        $id = isset($arr[1]) ? $arr[1] : 0;
        switch($role) {
            case self::ROLE_DOCTOR:
                /** @var Doctor $doctor */
                $doctor = Doctor::findOne($id);
                if (!is_null($doctor)) {
                    $data = self::getDoctorData($doctor);
                    return new static($data);
                }
                break;
            case self::ROLE_ADMIN:
                /** @var Admin $admin */
                $admin = Admin::findOne($id);
                if (!is_null($admin)) {
                    $data = self::getAdminData($admin);
                    return new static($data);
                }
                break;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token)
    {
        /** @var Doctor $doctor */
        $doctor = Doctor::find()->where(['login'=>$token])->one();
        if (!is_null($doctor)) {
            $data = self::getDoctorData($doctor);
            return new static($data);
        }
        /** @var Admin $admin */
        $admin = Admin::find()->where(['login'=>$token])->one();
        if (!is_null($admin)) {
            $data = self::getAdminData($admin);
            return new static($data);
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        /** @var Doctor $doctor */
        $doctor = Doctor::find()->where(['login'=>$username])->one();
        if (!is_null($doctor)) {
            $data = self::getDoctorData($doctor);
            return new static($data);
        }
        /** @var Admin $admin */
        $admin = Admin::find()->where(['login'=>$username])->one();
        if (!is_null($admin)) {
            $data = self::getAdminData($admin);
            return new static($data);
        }
        return null;

    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     * @param $doctor
     * @return array
     */
    private static function getDoctorData($doctor) {
        return [
            'id' => self::ROLE_DOCTOR . '-' . $doctor->id,
            'name' => $doctor->name,
            'username' => $doctor->login,
            'password' => $doctor->password,
            'authKey' => $doctor->id . 'testkey',
            'accessToken' => $doctor->login,
            'role' => self::ROLE_DOCTOR
        ];
    }

    /**
     * @param $admin Admin
     * @return array
     */
    private static function getAdminData($admin) {
        return [
            'id' => self::ROLE_ADMIN . '-' . $admin->id,
            'name' => $admin->name,
            'username' => $admin->login,
            'password' => $admin->password,
            'authKey' => $admin->id . 'testkey',
            'accessToken' => $admin->login,
            'role' => self::ROLE_ADMIN
        ];
    }

    public function getDoctor() {
        $arr = explode('-',$this->id);
        if ($arr[0] == self::ROLE_DOCTOR) {
            $id = isset($arr[1]) ? $arr[1] : 0;
            return Doctor::findOne($id);
        }
        return null;
    }

    public function getAdmin() {
        $arr = explode('-',$this->id);
        if ($arr[0] == self::ROLE_ADMIN) {
            $id = isset($arr[1]) ? $arr[1] : 0;
            return Admin::findOne($id);
        }
        return null;
    }

}
