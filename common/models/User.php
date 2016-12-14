<?php
namespace common\models;

use console\controllers\RbacController;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $img
 * @property string $email
 * @property string $name
 * @property string $phone
 * @property string $config
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $is_deleted
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const MIN_PASSWORD_LENGTH = 4;
    const MAX_PASSWORD_LENGTH = 32;

    const ROLE_ROOT = RbacController::ROLE_ROOT;
    const ROLE_ADMIN = RbacController::ROLE_ADMIN;
    const ROLE_MANAGER = RbacController::ROLE_MANAGER;
    const ROLE_SELLER = RbacController::ROLE_SELLER;
    const ROLE_BLOGGER = RbacController::ROLE_BLOGGER;
    const ROLE_CUSTOMER = RbacController::ROLE_CUSTOMER;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_deleted' => 0]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'is_deleted' => 0]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return NULL;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'is_deleted' => 0,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @return array
     */
    public static function availableRolesLabels()
    {
        $res = [];
        if (Yii::$app->user->isGuest) {
            return $res;
        }
        foreach (self::rolesLabels() as $role => $label) {
            if (Yii::$app->user->can($role)) {
                $res[$role] = $label;
            }
        }
        return $res;
    }

    /**
     * @param null $role
     * @return array|string
     */
    public static function rolesLabels($role = NULL)
    {
        $roles = [
            self::ROLE_ROOT => Yii::t('common', 'Root'),
            self::ROLE_ADMIN => Yii::t('common', 'Admin'),
            self::ROLE_MANAGER => Yii::t('common', 'Manager'),
            self::ROLE_SELLER => Yii::t('common', 'Seller'),
            self::ROLE_BLOGGER => Yii::t('common', 'Blogger'),
            self::ROLE_CUSTOMER => Yii::t('common', 'Customer'),
        ];
        if ($role === NULL) {
            return $roles;
        }

        return isset($roles[$role]) ? $roles[$role] : '';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'name'], 'required'],
            [['email'], 'email'],
            [['status', 'is_deleted', 'created_at', 'updated_at'], 'integer'],
            [['is_deleted'], 'default', 'value' => 0],
            [['img', 'email', 'name', 'phone', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['config'], 'string'],
            [['auth_key'], 'string', 'max' => 32],
            [['auth_key'], 'default', 'value' => Yii::$app->security->generateRandomString()],
            [['email', 'password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'img' => Yii::t('common', 'Img'),
            'email' => Yii::t('common', 'Email'),
            'name' => Yii::t('common', 'Name'),
            'phone' => Yii::t('common', 'Phone'),
            'config' => Yii::t('common', 'Config'),
            'auth_key' => Yii::t('common', 'Auth Key'),
            'password_hash' => Yii::t('common', 'Password Hash'),
            'password_reset_token' => Yii::t('common', 'Password Reset Token'),
            'is_deleted' => Yii::t('common', 'Is Deleted'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        foreach ($this->attributes as $key => $value) {
            if (empty($value)) {
                $this->setAttribute($key, NULL);
            }
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = NULL;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }
}
