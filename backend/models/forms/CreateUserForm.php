<?php

namespace backend\models\forms;

use backend\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @inheritdoc
 *
 * @property string $newPassword
 * @property string $newPasswordConfirm
 */
class CreateUserForm extends User
{
    public $newPassword;
    public $newPasswordConfirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            // new password
            ['newPassword', 'string', 'min' => User::PASSWORD_LENGTH_MIN, 'max' => User::PASSWORD_LENGTH_MAX],
            ['newPassword', 'filter', 'filter' => 'trim'],
            ['newPassword', 'required'],
            // new password confirm
            ['newPasswordConfirm', 'required'],
            ['newPasswordConfirm', 'compare', 'compareAttribute' => 'newPassword', 'message' => Yii::t('admin-side', 'Passwords do not match')],
        ]);
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
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->newPassword) && empty($this->password_hash)) {
                $this->setPassword($this->newPassword);
            }
            return TRUE;
        }
        return FALSE;
    }
}
