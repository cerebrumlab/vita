<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Модель пользователя
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 *
 * @package app\models
 */
class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return 'Users';
    }

    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['username'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password_hash' => 'Пароль',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = static::findOne(['username' => $username]);
        if ($user) {
            $userIdentity = [
                'id' => $user->id,
                'username' => $user->username,
                'password_hash' => $user->password_hash,
                'auth_key' => 'auth_key',
                'access_token' => 'access_token'
            ];
            return new static($userIdentity);
        }

        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($auth_key)
    {
        return $this->getAuthKey() === $auth_key;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->getSecurity()->generateRandomString();
            }
            $this->password_hash = md5($this->password_hash);
            return true;
        }
        return false;
    }

    /**
     * Validates password_hash
     *
     * @param  string  $password_hash password_hash to validate
     * @return boolean if password_hash provided is valid for current user
     */
    public function validatePassword($password_hash)
    {
        return $this->password_hash === md5($password_hash);
    }

}