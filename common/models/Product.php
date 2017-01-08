<?php

namespace common\models;

use xz1mefx\ufu\models\UfuActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

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
 * @property string             $name
 * @property string             $description
 *
 * @property bool               $canDelete
 * @property integer            $relationsCount
 *
 * @property array              $translates
 *
 * @property User               $updatedBy
 * @property User               $createdBy
 * @property Currency           $currency
 * @property User               $seller
 * @property ProductImage[]     $productImages
 * @property ProductTranslate[] $productTranslates
 * @property ProductTranslate   $productTranslate
 */
class Product extends UfuActiveRecord
{

    const TYPE_ID = 1;

    private $_translates;
    private $_canDelete;

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
    public function beforeValidate()
    {
        // validate translate fields in their models
        foreach (Yii::$app->lang->getLangList() as $lang) {
            if (isset($this->_translates[$lang['id']])) {
                $translateModel = new ProductTranslate();
                $translateModel->setAttributes($this->_translates[$lang['id']]);
                if (!$translateModel->validate(array_keys($this->_translates[$lang['id']]))) {
                    foreach ($translateModel->errors as $field => $error) {
                        $this->addError("translates[{$lang['id']}][{$field}]", $error);
                    }
                }
            }
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->canDelete) {
                return TRUE;
            }
            Yii::$app->session->setFlash('error', Yii::t('admin-side', 'You cannot delete this product'));
        }
        return FALSE;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // save or update translates
        /* @var $indexedTranslates ProductTranslate[] */
        $indexedTranslates = ArrayHelper::index($this->productTranslates, 'language_id');
        foreach ($this->translates as $langId => $fields) {
            if (isset($indexedTranslates[$langId])) { // update translate
                $indexedTranslates[$langId]->setAttributes($fields);
                $indexedTranslates[$langId]->save();
            } else { // insert new translate
                $translateModel = new ProductTranslate();
                $translateModel->product_id = $this->id;
                $translateModel->language_id = $langId;
                $translateModel->setAttributes($fields);
                $translateModel->save();
            }
        }
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
            // virtual UFU fields
            ['type', 'required'],
            ['type', 'integer'],
            ['type', 'in', 'range' => [self::TYPE_ID]],
            ['categories', 'required'],
            ['url', 'required'],
            ['url', 'validateUfuUrl'],
            // virtual multilang fields
            ['translates', 'safe'],
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
    public function afterDelete()
    {
        if ($this->ufuUrl) {
            $this->ufuUrl->delete();
        }
        foreach ($this->ufuCategoryRelations as $ufuCategoryRelation) {
            $ufuCategoryRelation->delete();
        }
        parent::afterDelete();
    }

    /**
     * @return int
     */
    public function getRelationsCount()
    {
        return $this->isNewRecord ? 0 : 0;
    }

    /**
     * @return bool
     */
    public function getCanDelete()
    {
        if (isset($this->_canDelete)) {
            return $this->_canDelete;
        }
        return $this->_canDelete = $this->relationsCount == 0;
    }

    /**
     * @return array
     */
    public function getTranslates()
    {
        if (isset($this->_translates)) {
            return $this->_translates;
        }
        $this->_translates = [];
        foreach (Yii::$app->lang->getLangList() as $lang) {
            $this->_translates[$lang['id']] = [
                'name' => NULL,
                'description' => NULL,
            ];
        }
        foreach ($this->productTranslates as $productTranslate) {
            if (isset($this->_translates[$productTranslate->language_id])) {
                $this->_translates[$productTranslate->language_id] = [
                    'name' => $productTranslate->name,
                    'description' => $productTranslate->description,
                ];
            }
        }
        return $this->_translates;
    }

    /**
     * @param $value array
     */
    public function setTranslates($value)
    {
        $this->_translates = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return empty($this->productTranslate->name) ? Yii::t('common', '<i>(has no translation)</i>') : $this->productTranslate->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return empty($this->productTranslate->description) ? Yii::t('common', '<i>(has no translation)</i>') : $this->productTranslate->description;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'status' => Yii::t('common', 'Status'),
            'currency_id' => Yii::t('common', 'Currency'),
            'seller_id' => Yii::t('common', 'Seller'),
            'image_src' => Yii::t('common', 'Image'),
            'price' => Yii::t('common', 'Price'),
            'viewed_count' => Yii::t('common', 'Viewed Count'),
            'viewed_date' => Yii::t('common', 'Viewed Date'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'name' => Yii::t('common', 'Name'),
            'description' => Yii::t('common', 'Description'),
            'currency.code' => Yii::t('common', 'Currency'),
            'currency.name' => Yii::t('common', 'Currency'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getProductTranslate()
    {
        return $this
            ->hasOne(ProductTranslate::className(), ['product_id' => 'id'])
            ->andOnCondition(['language_id' => Yii::$app->lang->id]);
    }

    /**
     * @inheritdoc
     */
    public function getUfuUrl()
    {
        return $this->getUfuUrlByType(self::TYPE_ID);
    }

    /**
     * @inheritdoc
     */
    public function getUfuCategoryRelations()
    {
        return $this->getUfuCategoryRelationsByType(self::TYPE_ID);
    }

}
