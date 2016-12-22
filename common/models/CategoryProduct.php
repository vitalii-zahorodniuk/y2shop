<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%category_product}}".
 *
 * @property integer  $id
 * @property integer  $category_id
 * @property integer  $product_id
 * @property integer  $created_by
 * @property integer  $updated_by
 * @property integer  $created_at
 * @property integer  $updated_at
 *
 * @property User     $updatedBy
 * @property Category $category
 * @property User     $createdBy
 * @property Product  $product
 */
class CategoryProduct extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_product}}';
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
            [['category_id', 'product_id'], 'required'],
            [['category_id', 'product_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['category_id', 'product_id'], 'unique', 'targetAttribute' => ['category_id', 'product_id'], 'message' => 'The combination of Category ID and Product ID has already been taken.'],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'category_id' => Yii::t('common', 'Category ID'),
            'product_id' => Yii::t('common', 'Product ID'),
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
