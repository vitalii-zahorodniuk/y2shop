<?php

namespace backend\models\forms;

use backend\models\User;
use Yii;
use yii\base\Model;

/**
 * Class ChangeUserPasswordForm
 *
 * @package backend\models\forms
 * @property string $newPassword
 * @property string $newPasswordConfirm
 */
class ChangeUserPasswordForm extends Model
{
    public $newPassword;
    public $newPasswordConfirm;

    private $_user;

    /**
     * ChangeUserPasswordForm constructor.
     * @param int $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $this->_user = User::findIdentity($id);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // new password
            ['newPassword', 'string', 'min' => User::PASSWORD_LENGTH_MIN, 'max' => User::PASSWORD_LENGTH_MAX],
            ['newPassword', 'filter', 'filter' => 'trim'],
            ['newPassword', 'required'],
            // new password confirm
            ['newPasswordConfirm', 'required'],
            ['newPasswordConfirm', 'compare', 'compareAttribute' => 'newPassword', 'message' => Yii::t('admin-side', 'Passwords do not match')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newPassword' => Yii::t('admin-side', 'Password'),
            'newPasswordConfirm' => Yii::t('admin-side', 'Confirm password'),
        ];
    }

    /**
     * Change user password
     *
     * @return boolean Result
     */
    public function change()
    {
        if ($this->validate()) {
            $this->_user->setPassword($this->newPassword);
            return $this->_user->save(false);
        }
        return false;
    }
}
