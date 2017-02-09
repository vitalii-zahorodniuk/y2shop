<?php

namespace common\models;

use xz1mefx\base\db\ActiveRecord;
use xz1mefx\multilang\models\Language;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%product_attribute}}".
 *
 * @property integer    $id
 * @property integer    $product_id
 * @property integer    $attribute_id
 * @property integer    $language_id
 * @property string     $value
 * @property integer    $created_by
 * @property integer    $updated_by
 * @property integer    $created_at
 * @property integer    $updated_at
 *
 * @property User       $updatedBy
 * @property Attribute  $attribute
 * @property User       $createdBy
 * @property MlLanguage $language
 * @property Product    $product
 */
class ProductAttribute extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_attribute}}';
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
            [['product_id', 'attribute_id', 'language_id', 'value'], 'required'],
            [['product_id', 'attribute_id', 'language_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['product_id', 'attribute_id', 'language_id'], 'unique', 'targetAttribute' => ['product_id', 'attribute_id', 'language_id'], 'message' => 'The combination of Product ID, Attribute ID and Language ID has already been taken.'],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['attribute_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Attribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => Yii::t('common', 'Product ID'),
            'attribute_id' => Yii::t('common', 'Attribute ID'),
            'language_id' => Yii::t('common', 'Language ID'),
            'value' => Yii::t('common', 'Value'),
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
    public function getAttribute()
    {
        return $this->hasOne(Attribute::className(), ['id' => 'attribute_id']);
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
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
