<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer             $id
 * @property integer             $status
 * @property string              $code
 * @property integer             $created_by
 * @property integer             $updated_by
 * @property integer             $created_at
 * @property integer             $updated_at
 *
 * @property bool                $canDelete
 * @property integer             $relationsCount
 *
 * @property array               $translates
 *
 * @property string              $name
 * @property string              $symbolLeft
 * @property string              $symbolRight
 *
 * @property User                $updatedBy
 * @property User                $createdBy
 * @property CurrencyTranslate[] $currencyTranslates
 * @property CurrencyTranslate   $currencyTranslate
 * @property Product[]           $products
 */
class Currency extends ActiveRecord
{

    private $_translates;
    private $_canDelete;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @return array
     */
    public static function getDrDownList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
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
                $translateModel = new CurrencyTranslate();
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
            Yii::$app->session->setFlash('error', Yii::t('admin-side', 'You can delete the currency only without relations'));
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
        /* @var $indexedTranslates CurrencyTranslate[] */
        $indexedTranslates = ArrayHelper::index($this->currencyTranslates, 'language_id');
        foreach ($this->translates as $langId => $fields) {
            if (isset($indexedTranslates[$langId])) { // update translate
                $indexedTranslates[$langId]->setAttributes($fields);
                $indexedTranslates[$langId]->save();
            } else { // insert new translate
                $translateModel = new CurrencyTranslate();
                $translateModel->currency_id = $this->id;
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
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'required'],
            [['code'], 'string', 'max' => 255],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'status' => Yii::t('common', 'Status'),
            'code' => Yii::t('common', 'Code (ISO 4217)'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'name' => Yii::t('common', 'Name'),
            'symbol_left' => Yii::t('common', 'Symbol Left'),
            'symbol_right' => Yii::t('common', 'Symbol Right'),
            'relationsCount' => Yii::t('common', 'Product relations count'),
        ];
    }

    /**
     * @return int
     */
    public function getRelationsCount()
    {
        return $this->isNewRecord ? 0 : Product::find()->where(['currency_id' => $this->id])->count('id');
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
                'symbol_left' => NULL,
                'symbol_right' => NULL,
            ];
        }
        foreach ($this->currencyTranslates as $currencyTranslate) {
            if (isset($this->_translates[$currencyTranslate->language_id])) {
                $this->_translates[$currencyTranslate->language_id] = [
                    'name' => $currencyTranslate->name,
                    'symbol_left' => $currencyTranslate->symbol_left,
                    'symbol_right' => $currencyTranslate->symbol_right,
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
        return empty($this->currencyTranslate->name) ? Yii::t('common', '<i>(has no translation)</i>') : $this->currencyTranslate->name;
    }

    /**
     * @return string
     */
    public function getSymbolLeft()
    {
        return empty($this->currencyTranslate) ? Yii::t('common', '<i>(has no translation)</i>') : $this->currencyTranslate->symbol_left;
    }

    /**
     * @return string
     */
    public function getSymbolRight()
    {
        return empty($this->currencyTranslate) ? Yii::t('common', '<i>(has no translation)</i>') : $this->currencyTranslate->symbol_right;
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
    public function getCurrencyTranslates()
    {
        return $this->hasMany(CurrencyTranslate::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyTranslate()
    {
        return $this
            ->hasOne(CurrencyTranslate::className(), ['currency_id' => 'id'])
            ->andOnCondition(['language_id' => Yii::$app->lang->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyRates()
    {
        return $this->hasMany(CurrencyRate::className(), ['currency_from_id' => 'id']);
    }

}
