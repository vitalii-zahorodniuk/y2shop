<?php

namespace common\models;

use xz1mefx\ufu\models\UfuUrl;
use xz1mefx\ufu\models\UrlActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer            $id
 * @property integer            $status
 * @property integer            $currency_id
 * @property integer            $seller_id
 * @property string             $image_src
 * @property string             $price
 * @property integer            $viewed_count
 * @property string             $viewed_date
 * @property integer            $created_by
 * @property integer            $updated_by
 * @property integer            $created_at
 * @property integer            $updated_at
 *
 * @property User               $updatedBy
 * @property User               $createdBy
 * @property Currency           $currency
 * @property User               $seller
 * @property ProductImage[]     $productImages
 * @property ProductTranslate[] $productTranslates
 */
class Product extends UrlActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
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
            [['status', 'currency_id', 'seller_id', 'viewed_count', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['currency_id', 'price'], 'required'],
            [['price'], 'number'],
            [['viewed_date'], 'safe'],
            [['image_src'], 'string', 'max' => 255],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['seller_id' => 'id']],
            // virtual url field
            ['url', 'validateUfuUrl'],
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
            'status' => Yii::t('common', 'Status'),
            'currency_id' => Yii::t('common', 'Currency ID'),
            'seller_id' => Yii::t('common', 'Seller ID'),
            'image_src' => Yii::t('common', 'Image Src'),
            'price' => Yii::t('common', 'Price'),
            'viewed_count' => Yii::t('common', 'Viewed Count'),
            'viewed_date' => Yii::t('common', 'Viewed Date'),
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
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(User::className(), ['id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTranslates()
    {
        return $this->hasMany(ProductTranslate::className(), ['product_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getUfuUrl()
    {
        return $this->hasOne(UfuUrl::className(), ['item_id' => 'id'])
            ->andOnCondition(['is_category' => 0, 'type' => 1]);
    }
}
