<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $status
 * @property string  $img
 * @property string  $email
 * @property string  $name
 * @property string  $phone
 * @property string  $config
 * @property string  $auth_key
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property boolean $userDeleted
 * @property boolean $userActivated
 * @property boolean $userOnHold
 *
 * @property User    $updatedBy
 * @property User    $createdBy
 * @property array   $rolesArray
 * @property boolean $youCanEdit
 */
class User extends ActiveRecord implements IdentityInterface, UserInterface
{

    protected $_youCanEdit;
    protected $_rolesArray;
    protected $_childrenRolesArray;

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
        return static::findOne(['id' => $id]);
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
     *
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return NULL;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return FALSE;
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
     *
     * @return array|string
     */
    public static function rolesLabels($role = NULL)
    {
        $roles = [
            self::ROLE_ROOT => Yii::t('common', 'Root role'),
            self::ROLE_ADMIN => Yii::t('common', 'Admin role'),
            self::ROLE_MANAGER => Yii::t('common', 'Manager role'),
            self::ROLE_SELLER => Yii::t('common', 'Seller role'),
            self::ROLE_CUSTOMER => Yii::t('common', 'Customer role'),
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
     * @param $attribute
     * @param $params
     */
    public function validateRolesArray($attribute, $params)
    {
        if (is_array($this->_rolesArray)) {
            foreach ($this->_rolesArray as $role) {
                if (!Yii::$app->user->can($role)) {
                    $this->addError($attribute, Yii::t('common', 'You do not have permission to assign such rights'));
                    // TODO: Inform admin?
                    break;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // status
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::statusesLabels())],
            // image
            ['img', 'string', 'max' => 255],
            // email
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            // name
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            // phone
            ['phone', 'string', 'max' => 255],
            // config
            ['config', 'string'],
            // auth key
            ['auth_key', 'string', 'max' => 32],
            ['auth_key', 'default', 'value' => Yii::$app->security->generateRandomString()],
            // password hash
            ['password_hash', 'string', 'max' => 255],
            // password reset token
            ['password_reset_token', 'string', 'max' => 255],
            ['password_reset_token', 'unique'],
            // created-updated timestamps
            [['created_by', 'updated_by'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            // created-updated timestamps
            [['created_at', 'updated_at'], 'integer'],

            // virtual
            [['rolesArray'], 'safe'],
            [['rolesArray'], 'required'],
            [['rolesArray'], 'validateRolesArray'],
        ];
    }

    /**
     * @param null|string $status
     *
     * @return array|string
     */
    public static function statusesLabels($status = NULL)
    {
        $statuses = [
            self::STATUS_DELETED => Yii::t('common', 'User deleted'),
            self::STATUS_ON_HOLD => Yii::t('common', 'User on hold'),
            self::STATUS_ACTIVE => Yii::t('common', 'User active'),
        ];
        if ($status === NULL) {
            return $statuses;
        }

        return isset($statuses[$status]) ? $statuses[$status] : '';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$this->youCanEdit) {
            Yii::$app->session->setFlash('danger', Yii::t('common', 'You do not have permission to edit this user'));
            return FALSE;
        }

        if ($insert) {
            $this->created_by = Yii::$app->user->id;
        } else {
            $this->updated_by = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'status' => Yii::t('common', 'Status'),
            'img' => Yii::t('common', 'Img'),
            'email' => Yii::t('common', 'Email'),
            'name' => Yii::t('common', 'Name'),
            'phone' => Yii::t('common', 'Phone'),
            'config' => Yii::t('common', 'Config'),
            'auth_key' => Yii::t('common', 'Auth Key'),
            'password_hash' => Yii::t('common', 'Password Hash'),
            'password_reset_token' => Yii::t('common', 'Password Reset Token'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            // virtual
            'roles' => Yii::t('common', 'Roles'),
            'rolesArray' => Yii::t('common', 'Roles'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
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
        // Refresh user roles
        if (is_array($this->_rolesArray)) {
            // Add new roles
            foreach ($this->_rolesArray as $role) {
                if (in_array($role, $this->getCurrentUserRoles())) {
                    continue;
                }
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole($role), $this->id);
            }
            // Remove other roles
            foreach ($this->getCurrentUserRoles() as $role) {
                if (in_array($role, $this->_rolesArray)) {
                    continue;
                }
                Yii::$app->authManager->revoke(Yii::$app->authManager->getRole($role), $this->id);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return string[]
     */
    private function getCurrentUserRoles()
    {
        return ArrayHelper::getColumn(
            Yii::$app->authManager->getRolesByUser(
                $this->id
            ),
            'name'
        );
    }

    /**
     * @return bool
     */
    public function getYouCanEdit()
    {
        if (isset($this->_youCanEdit)) {
            return $this->_youCanEdit;
        }
        if (!Yii::$app->user->identity->userActivated) {
            return $this->_youCanEdit = FALSE;
        }
        if (!Yii::$app->user->isGuest) {
            foreach ($this->getCurrentUserRoles() as $role) {
                if (!Yii::$app->user->can($role)) {
                    return $this->_youCanEdit = FALSE;
                }
            }
        }
        return $this->_youCanEdit = TRUE;
    }

    /**
     * @return array
     */
    public function getChildrenRolesArray()
    {
        if (isset($this->_childrenRolesArray)) {
            return $this->_childrenRolesArray;
        }
        $this->_childrenRolesArray = [];
        foreach (array_keys(self::rolesLabels()) as $role) {
            if (!in_array($role, $this->getRolesArray()) && Yii::$app->authManager->checkAccess($this->id, $role)) {
                $this->_childrenRolesArray[] = $role;
            }
        }
        return $this->_childrenRolesArray;
    }

    /**
     * @return array
     */
    public function getRolesArray()
    {
        if (isset($this->_rolesArray)) {
            return $this->_rolesArray;
        }
        return $this->_rolesArray = $this->getCurrentUserRoles();
    }

    /**
     * @param string[] $value
     */
    public function setRolesArray($value)
    {
        $this->_rolesArray = $value;
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
     *
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

    /**
     * @return bool
     */
    public function getUserDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    /**
     * @return bool
     */
    public function getUserActivated()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function getUserOnHold()
    {
        return $this->status == self::STATUS_ON_HOLD;
    }
}
