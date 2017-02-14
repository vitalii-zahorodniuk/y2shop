<?php

namespace common\models;

use xz1mefx\base\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property integer           $id
 * @property integer           $parent_id
 * @property integer           $status
 * @property integer           $order
 * @property integer           $created_by
 * @property integer           $updated_by
 * @property integer           $created_at
 * @property integer           $updated_at
 *
 * @property array             $translates
 * @property string            $name
 * @property string            $parentName
 *
 * @property User              $updatedBy
 * @property User              $createdBy
 * @property OptionTranslate[] $optionTranslates
 * @property OptionTranslate   $optionTranslate
 * @property OptionTranslate   $parentOptionTranslate
 * @property ProductOption[]   $productOptions
 * @property Product[]         $products
 * @property Option            $parent
 */
class Option extends ActiveRecord
{

    const STATUS_DELETED = -1;
    const STATUS_ON_HOLD = 0;
    const STATUS_ACTIVE = 1;

    private $_translates;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option}}';
    }

    /**
     * @return array
     */
    public static function getGroupDrDownList()
    {
        return ArrayHelper::map(
            self::find()
                ->with('optionTranslate')
                ->where([
                    'status' => self::STATUS_ACTIVE,
                    'parent_id' => 0,
                ])
                ->all(),
            'id',
            'name'
        );
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
                $translateModel = new OptionTranslate();
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
    public function afterSave($insert, $changedOptions)
    {
        parent::afterSave($insert, $changedOptions);

        // save or update translates
        /* @var $indexedTranslates OptionTranslate[] */
        $indexedTranslates = ArrayHelper::index($this->optionTranslates, 'language_id');
        foreach ($this->translates as $langId => $fields) {
            if (isset($indexedTranslates[$langId])) { // update translate
                $indexedTranslates[$langId]->setAttributes($fields);
                $indexedTranslates[$langId]->save();
            } else { // insert new translate
                $translateModel = new OptionTranslate();
                $translateModel->option_id = $this->id;
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
            // parent_id
            ['parent_id', 'integer'],
            ['parent_id', 'default', 'value' => 0],
            // status
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::statusesLabels())],
            // order
            ['order', 'integer'],
            ['order', 'default', 'value' => 0],
            // others
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            // virtual multilang fields
            [['translates'], 'safe'],
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
            self::STATUS_DELETED => Yii::t('common', 'Option deleted'),
            self::STATUS_ON_HOLD => Yii::t('common', 'Option on hold'),
            self::STATUS_ACTIVE => Yii::t('common', 'Option active'),
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
            'parent_id' => Yii::t('common', 'Parent ID'),
            'status' => Yii::t('common', 'Status'),
            'order' => Yii::t('common', 'Order'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'name' => Yii::t('common', 'Name'),
            'parentName' => Yii::t('common', 'Option group'),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return empty($this->optionTranslate->name) ? Yii::t('common', '<i>(has no translation)</i>') : $this->optionTranslate->name;
    }

    /**
     * @return string
     */
    public function getParentName()
    {
        if ($this->parent_id == 0) {
            return '';
        }
        return empty($this->parentOptionTranslate->name) ? Yii::t('common', '<i>(has no translation)</i>') : $this->parentOptionTranslate->name;
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
            ];
        }
        foreach ($this->optionTranslates as $optionTranslate) {
            if (isset($this->_translates[$optionTranslate->language_id])) {
                $this->_translates[$optionTranslate->language_id] = [
                    'name' => $optionTranslate->name,
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
    public function getOptionTranslates()
    {
        return $this->hasMany(OptionTranslate::className(), ['option_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptionTranslate()
    {
        return $this
            ->hasOne(OptionTranslate::className(), ['option_id' => 'id'])
            ->from(['ot' => OptionTranslate::tableName()])
            ->andOnCondition(['ot.language_id' => Yii::$app->lang->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentOptionTranslate()
    {
        return $this
            ->hasOne(OptionTranslate::className(), ['option_id' => 'parent_id'])
            ->from(['pot' => OptionTranslate::tableName()])
            ->andOnCondition(['pot.language_id' => Yii::$app->lang->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOptions()
    {
        return $this->hasMany(ProductOption::className(), ['option_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('{{%product_option}}', ['option_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this
            ->hasOne(self::className(), ['id' => 'parent_id'])
            ->from(['p' => self::tableName()]);
    }
}
