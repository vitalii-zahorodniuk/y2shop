<?php

namespace common\models;

use xz1mefx\base\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%currency_rate}}".
 *
 * @property integer  $id
 * @property integer  $currency_from_id
 * @property integer  $currency_to_id
 * @property string   $coefficient
 * @property integer  $created_by
 * @property integer  $updated_by
 * @property integer  $created_at
 * @property integer  $updated_at
 *
 * @property User     $updatedBy
 * @property User     $createdBy
 * @property Currency $currencyFrom
 * @property Currency $currencyTo
 */
class CurrencyRate extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency_rate}}';
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
            [['currency_from_id', 'currency_to_id', 'coefficient'], 'required'],
            [['currency_from_id', 'currency_to_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['coefficient'], 'number'],
            [['currency_from_id', 'currency_to_id'], 'unique', 'targetAttribute' => ['currency_from_id', 'currency_to_id'], 'message' => 'The combination of Currency From ID and Currency To ID has already been taken.'],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['currency_from_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_from_id' => 'id']],
            [['currency_to_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_to_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
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
            'currency_from_id' => Yii::t('common', 'Currency From ID'),
            'currency_to_id' => Yii::t('common', 'Currency To ID'),
            'coefficient' => Yii::t('common', 'Currency coefficient'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyFrom()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_from_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyTo()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_to_id']);
    }
}
