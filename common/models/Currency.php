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
 * @property integer             $is_default
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
 * @property array               $rates
 * @property array               $inverseRates
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
 * @property CurrencyRate[]      $currencyRates
 */
class Currency extends ActiveRecord
{

    private static $_all;
    private $_translates;
    private $_rates;
    private $_inverseRates;
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
        // validate rates fields in their models
        foreach (self::getAll($this->id) as $currency) {
            if (isset($this->_rates[$currency->id])) {
                $currencyRateModel = new CurrencyRate();
                $currencyRateModel->currency_to_id = $currency->id;
                $currencyRateModel->coefficient = $this->_rates[$currency->id];
                if (!$currencyRateModel->validate(['currency_to_id', 'coefficient'])) {
                    foreach ($currencyRateModel->errors as $error) {
                        $this->addError("rates[{$currency->id}]", $error);
                    }
                }
            }
        }

        if ($this->isNewRecord) {
            // validate inverse rates fields in their models
            foreach (self::getAll($this->id) as $currency) {
                if (isset($this->_rates[$currency->id])) {
                    $currencyRateModel = new CurrencyRate();
                    $currencyRateModel->currency_from_id = $currency->id;
                    $currencyRateModel->coefficient = $this->_inverseRates[$currency->id];
                    if (!$currencyRateModel->validate(['currency_from_id', 'coefficient'])) {
                        foreach ($currencyRateModel->errors as $error) {
                            $this->addError("inverseRates[{$currency->id}]", $error);
                        }
                    }
                }
            }
        }

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
     * @param null|integer $ignoreId
     *
     * @return self[]
     */
    public static function getAll($ignoreId = NULL)
    {
        if (empty(self::$_all)) {
            self::$_all = self::find()->indexBy('id')->all();
        }

        if ($ignoreId == NULL) {
            return self::$_all;
        }

        $res = self::$_all;
        if (isset($res[$ignoreId])) {
            unset($res[$ignoreId]);
        }
        return $res;
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
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->is_default) {
            self::updateAll(['is_default' => 0], ['!=', 'id', $this->id]);
        }

        // save or update rates
        /* @var $indexedRates CurrencyRate[] */
        $indexedRates = ArrayHelper::index($this->currencyRates, 'currency_to_id');
        foreach ($this->rates as $currencyToId => $coefficient) {
            if (isset($indexedRates[$currencyToId])) { // update translate
                $indexedRates[$currencyToId]->coefficient = $coefficient;
                $indexedRates[$currencyToId]->save();
            } else { // insert new translate
                $currencyRateModel = new CurrencyRate();
                $currencyRateModel->currency_from_id = $this->id;
                $currencyRateModel->currency_to_id = $currencyToId;
                $currencyRateModel->coefficient = $coefficient;
                $currencyRateModel->save();
            }
        }

        if ($insert) {
            // save or update inverse rates
            /* @var $indexedRates CurrencyRate[] */
            foreach ($this->inverseRates as $currencyFromId => $coefficient) {
                $currencyRateModel = new CurrencyRate();
                $currencyRateModel->currency_from_id = $currencyFromId;
                $currencyRateModel->currency_to_id = $this->id;
                $currencyRateModel->coefficient = $coefficient;
                $currencyRateModel->save();
            }
        }

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
            [['is_default', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            ['is_default', 'in', 'range' => [0, 1]],
            ['is_default', 'default', 'value' => 0],
            [['code'], 'required'],
            [['code'], 'string', 'max' => 255],
            [['updated_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => TRUE, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            // virtual multilang fields
            [['rates', 'inverseRates', 'translates'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'is_default' => Yii::t('common', 'Is default currency'),
            'status' => Yii::t('common', 'Status'),
            'code' => Yii::t('common', 'Code (ISO 4217)'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'name' => Yii::t('common', 'Name'),
            'symbol_left' => Yii::t('common', 'Symbol Left'),
            'symbol_right' => Yii::t('common', 'Symbol Right'),
            'rates' => Yii::t('common', 'Rates coefficients'),
            'inverce_rates' => Yii::t('common', 'Inverse rates coefficients'),
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
     * @return array
     */
    public function getRates()
    {
        if (isset($this->_rates)) {
            return $this->_rates;
        }
        $this->_rates = [];
        foreach (self::getAll($this->id) as $currency) {
            $this->_rates[$currency->id] = NULL;
        }
        foreach ($this->currencyRates as $currencyRate) {
            if (is_null($this->_rates[$currencyRate->currency_to_id]) || isset($this->_rates[$currencyRate->currency_to_id])) {
                $this->_rates[$currencyRate->currency_to_id] = $currencyRate->coefficient;
            }
        }
        return $this->_rates;
    }

    /**
     * @param $value array
     */
    public function setRates($value)
    {
        $this->_rates = $value;
    }

    /**
     * @return array
     */
    public function getInverseRates()
    {
        if (isset($this->_inverseRates)) {
            return $this->_inverseRates;
        }
        $this->_inverseRates = [];
        foreach (self::getAll($this->id) as $currency) {
            $this->_inverseRates[$currency->id] = NULL;
        }
        return $this->_inverseRates;
    }

    /**
     * @param $value array
     */
    public function setInverseRates($value)
    {
        $this->_inverseRates = $value;
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
        return $this->hasMany(CurrencyRate::className(), ['currency_from_id' => 'id'])->with(['currencyTo']);
    }


    /**
     * @param $currencyCode
     *
     * @return bool
     */
    public static function checkCurrency($currencyCode)
    {
        return self::find()->where(['code' => $currencyCode])->exists();
    }

}
