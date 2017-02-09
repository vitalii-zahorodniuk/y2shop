<?php

namespace common\models;

use xz1mefx\base\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%filter}}".
 *
 * @property integer           $id
 * @property integer           $status
 * @property integer           $created_by
 * @property integer           $updated_by
 * @property integer           $created_at
 * @property integer           $updated_at
 *
 * @property User              $updatedBy
 * @property User              $createdBy
 * @property FilterTranslate[] $filterTranslates
 * @property ProductFilter[]   $productFilters
 * @property Product[]         $products
 */
class Filter extends ActiveRecord
{

    const STATUS_DELETED = -1;
    const STATUS_ON_HOLD = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter}}';
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
            // status
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::statusesLabels())],
            // others
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            self::STATUS_DELETED => Yii::t('common', 'Filter deleted'),
            self::STATUS_ON_HOLD => Yii::t('common', 'Filter on hold'),
            self::STATUS_ACTIVE => Yii::t('common', 'Filter active'),
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
    public function getFilterTranslates()
    {
        return $this->hasMany(FilterTranslate::className(), ['filter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFilters()
    {
        return $this->hasMany(ProductFilter::className(), ['filter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('{{%product_filter}}', ['filter_id' => 'id']);
    }
}
