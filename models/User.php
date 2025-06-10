<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

class User extends ActiveRecord implements IdentityInterface
{
    public $profilePictureFile; // Temporary attribute for file upload
    public $retype_password;    // Temporary attribute for password retype

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
{
    return [
        [['username', 'email', 'password_hash'], 'required'],
        [['username', 'email'], 'string', 'max' => 255],
        [['email'], 'email'],
        [['username', 'email'], 'unique'],
        [['created_at', 'updated_at'], 'safe'],
        [['profile_picture'], 'string', 'max' => 255],
        [['profilePictureFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        [['retype_password'], 'string'], // Tumia hii tu kama placeholder
    ];
}

    // IdentityInterface methods
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // This method is not used in our basic authentication setup
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

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    // Password hashing and validation
    public function setPassword($password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    public function uploadProfilePicture()
    {
        if ($this->profilePictureFile instanceof UploadedFile) {
            $fileName = 'uploads/profiles/' . Yii::$app->security->generateRandomString() . '.' . $this->profilePictureFile->extension;
            if ($this->profilePictureFile->saveAs($fileName)) {
                $this->profile_picture = $fileName;
                return true;
            }
        }
        return false;
    }
}